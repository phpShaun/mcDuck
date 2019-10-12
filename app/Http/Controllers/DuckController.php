<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\DucksTrait;
use App\Duck;

class DuckController extends Controller
{
    use DucksTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the ducks dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        // Get The Users Duck
        $duck = \App\Duck::where([
            ['user_id', Auth::id()],
            ['status', "!=", "Deceased"]
        ])->first();

        // If have a duck that is not deceased
        if( !empty($duck) ) {
            return view('ducks.index', ['duck' => $duck, 'has_duck' => true]);
        } else {
            // Check to see if they have a duck that is deceased
            $duck = \App\Duck::where([
                ['user_id', Auth::id()],
                ['status', "Deceased"]
            ])->orderBy('born', 'desc')->first();

            // If we do, return that to display RIP message
            if( !empty($duck) ) {
                return view('ducks.index', ['duck' => $duck, 'has_duck' => true]);
            }
        }
        return view('ducks.index', ['has_duck', false]);
    }

    /**
     * Display the Create View
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {

        $has_duck = false;
        $duck = \App\Duck::where('user_id', Auth::id())->first();

        // If the user does not have a duck, allow the creation
        if( !empty($duck) ) {
            if($duck->status !== "Deceased") {
                $has_duck = true;
            }
        }

        return view('ducks.create', ['has_duck' => $has_duck]);
    }

    /**
     * Store the Duck
     *
     * @return Redirect | Errors
     */
    public function store(Request $request) {

        $duck = new Duck();

        $sex = ['Male', 'Female'];

        // Build Duck Model
        $duck->user_id          = Auth::id();
        $duck->name             = $request->name;
        $duck->born             = gmdate("Y-m-d H:i:s");
        $duck->last_fed         = gmdate("Y-m-d H:i:s");
        $duck->last_interaction = gmdate("Y-m-d H:i:s");
        $duck->sex              = $sex[rand(0,1)];

        // Save Duck
        if( $duck->save() ) {
            return redirect('/ducks');
        }
        return back()->withErrors(['error' => 'Unfortunately your duck had some complications during birth.']);
    }

    /**
     * Feed the duck
     *
     * @return response json
     */
    public function feed($id) {

        $duck = \App\Duck::find($id);
        $duck->last_interaction = gmdate("Y-m-d H:i:s");

        $min = $this->min();
        $max = $this->max();

        // if being fed and the hunger is already satiated
        if($duck->hunger == $min['hunger']) {
            // random-ish chance to cause illness by over feeding
            $duck->illness = rand(0,1);
        }

        if( $duck->status == 'Healthy' ) {
            // If the duck is healthy we slowly age the duck
            $modifiers = $this->healthyModifiers();
        } else {
            // If the duck is ill we fastly age the duck
            $modifiers = $this->illnessModifiers();
        }

        // duck is overweight, add chance to cause illness
        if( $duck->weight >= $max['weight'] ) {
            $duck->illness = rand(0,1);
        }

        // If Duck Hunger is less than or equal to the max hunger decrease hunger
        if( $duck->hunger <= $max['hunger'] ) {
            $duck->hunger -= $modifiers['hunger']['subtract'];
        }

        // if duck health is less than  max, increase by 1
        if( $duck->health < $max['health'] ) {
            $duck->health += 1;
        }

        // if hunger is less or equal to 7, the duck is healthy
        if($duck->hunger <= 7) {
            $duck->status = "Healthy";
        }

        // if the duck does not have an illness increase its happyness
        if( !$duck->illness ) {
            if( $duck->happyness <= $max['happyness'] ) {
                $duck->happyness += $modifiers['happyness']['add'];
            }
        }

        //set last fed
        $duck->last_fed = gmdate("Y-m-d H:i:s");

        //Increase weight
        $duck->weight  += $modifiers['weight']['add'];

        if( $duck->save() ) {
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unfortunately '.$duck->name.' was not fed successfully.'
        ]);
    }

    /**
     * Heal the duck
     *
     * @return Response json
     */
    public function heal($id) {

        $duck = \App\Duck::find($id);
        $duck->last_interaction = gmdate("Y-m-d H:i:s");

        // set illness to 0
        $duck->illness = 0;

        //set status to Healthy
        $duck->status  = "Healthy";

        if( $duck->save() ) {
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unfortunately '.$duck->name.' was not healed successfully.'
        ]);
    }

    /**
     * Play with the duck
     *
     * @return Response json
     */
    public function play($id) {
        $duck = \App\Duck::find($id);
        $duck->last_interaction = gmdate("Y-m-d H:i:s");

        $min = $this->min();
        $max = $this->max();

        if( $duck->status == 'Healthy' ) {
            // If the duck is healthy we slowly age the duck
            $modifiers = $this->healthyModifiers();
        } else {
            // If the duck is ill we fastly age the duck
            $modifiers = $this->illnessModifiers();
        }

        // if playing with the duck and the hunger is max
        if( $duck->hunger == $max['hunger'] ) {
            $duck->health    -= 1;
            $duck->happyness -= $modifiers['happyness']['subtract'];
        }

        // if the weight is greater than the healty weight, reduce the weight because the duck is exercising
        if( $duck->weight > $min['weight']['healthy'] ) {
            $duck->weight -= $modifiers['weight']['subtract'];
        }

        // if the weight is less than max weight and the duck status is overweight update the status to Healthy
        if( $duck->weight < $max['weight'] && $duck->status == 'Overweight' ) {
            $duck->status = "Healthy";
        }

        // If the duck has an illness reduce happyness, the duck doesnt want to play when its sick
        if( $duck->illness ) {
            $duck->happyness--;
        } else {
            $duck->happyness++;
        }

        // if the hunger is less than max hunger increase the hunger because the duck is exercising
        if( $duck->hunger < $max['hunger'] ) {
            $duck->hunger += $modifiers['hunger']['add'];
        }
        
        if( $duck->save() ) {
            return response()->json([
                'success' => true
            ]);
        }
    }
}
