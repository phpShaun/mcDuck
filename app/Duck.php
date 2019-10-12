<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Traits\DucksTrait;

class Duck extends Model
{
    use DucksTrait;
    /**
     * Get the Name of the Duck
     *
     * @param string $name
     * @return string
     */
    public function getNameAttribute($name) {
        return ucfirst($name);
    }

    /**
     * Set the name of the Duck
     *
     * @param string $name
     */
    public function setNameAttribute($name) {
        $this->attributes['name'] = strtolower($name);
    }

    /**
     * Observe the Saving Process so we can modify some data if needed, like setting the status, and making sure we dont wander out of our ranges
     *
     */
    protected static function boot() {
        parent::boot();
        static::saving(function ($duck) {
            // We only want to check the values if this is an existing duck
            if( !empty($duck->id) ) {

                // Get Min and Max values from Traits
                $max = $duck->max();
                $min = $duck->min();

                // Don't allow values above max
                if( $duck->hunger > $max['hunger'] )
                    $duck->hunger = $max['hunger'];

                if( $duck->happyness > $max['happyness'] )
                    $duck->happyness = $max['happyness'];

                if( $duck->health > $max['health'] )
                    $duck->health = $max['health'];
                //end 

                // if the duck is happy and its larger than 1.2lb give it a chance to recover some health naturally
                if($duck->happyness >= 6 && $duck->weight >= 1.2) {
                    // If the ducks health is below max threshold
                    if( $duck->health < $max['health'] ) {
                        // Give it a chance to naturally renew health
                        $chance = rand(0,4);
                        if($chance == 2) {
                            $duck->health += 1;
                        }
                    }
                }

                // Don't allow values to go negative
                $duck->hunger    = max($duck->hunger,0);
                $duck->health    = max($duck->health,0);
                $duck->happyness = max($duck->happyness,0);
                $duck->weight    = max($duck->weight,0);
                //end

                // If the Ducks weight is greater than the max healthy weight set status to Overweight
                if( $duck->weight > $max['weight'] ) {
                    $duck->status = "Overweight";
                }

                // If the hunger is less than greater than 8 set status to Hungry
                if( $duck->hunger >= 8) {
                    $duck->status = "Hungry";
                }

                // If the duck has an illness set status to Sick
                if( $duck->illness ) {
                    $duck->status = "Sick";
                }

                // If the ducks health, weight or happyness is at a minimum threshold, set status to Deceased
                if( $duck->health == $min['health'] || $duck->weight == $min['weight']['unhealthy'] || $duck->happyness == $min['happyness']) {
                    $duck->status = "Deceased";
                }
            }
        });
    }
}
