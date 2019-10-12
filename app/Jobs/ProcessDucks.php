<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

// Use DucksTrait
use App\Traits\DucksTrait;

class ProcessDucks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, DucksTrait;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        // Pull Ducks that are not Deceased
        $ducks = \App\Duck::where("status", "!=", "Deceased")->get();

        if( !empty($ducks) ) {
            foreach($ducks as $duck) {

                // Get the Ducks Modifiers
                if( $duck->status == 'Healthy' ) {
                    // If the duck is healthy we slowly age the duck
                    $modifiers = $this->healthyModifiers();
                } else {
                    // If the duck is ill we fastly age the duck
                    $modifiers = $this->illnessModifiers();
                }

                // if the last time the duck was fed is more than 30 minutes ago modify the ducks hunger attribute
                if( strtotime($duck->last_fed) < strtotime('-30 minutes') ) {
                    if( $duck->hunger == 10 ) {
                        // if the duck is at max hunger, decrease health by 1
                        $duck->health--;
                        if($duck->weight >= 0.1) {
                            // Decrease the ducks weight because it has not been fed
                            $duck->weight -= $modifiers['weight']['subtract']; 
                        }
                    } else {
                        // if the duck is below 10 hunger, increase by 1
                        $duck->hunger += $modifiers['hunger']['add'];
                    }
                }

                // if the ducks last interaction was more than 30 minutes ago, we need to modify its happyness attribute
                if( strtotime($duck->last_interaction) < strtotime('-30 minutes') ) {
                    if( $duck->happyness !== 0) {
                        $duck->happyness -= $modifiers['happyness']['subtract'];
                    }
                }

                $duck->save();
            }
        }
    }
}
