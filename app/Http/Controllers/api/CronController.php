<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CronController extends Controller
{
    public function cron()
    {
        // 1 PV = 3$
        $pvValue = 3;
        $bonusPercentage = 1.0;

        $users = User::all();

        foreach ($users as $user) {
            $totalLeftPv = $user->getTotalLeftPoints();
            $totalRightPv = $user->getTotalRightPoints();

            // Calculate new available points since last matching
            $finalLeftPv = $totalLeftPv - $user->left_points;
            $finalRightPv = $totalRightPv - $user->right_points;

            // Matching pair
            $matchedPv = min($finalLeftPv, $finalRightPv);

            if ($matchedPv > 0) {
                $bonusAmount = $matchedPv * $pvValue * $bonusPercentage;

                // Add bonus to shopping wallet
                $user->income_wallet += $bonusAmount;

                // Update only matched PVs
                $user->left_points += $matchedPv;
                $user->right_points += $matchedPv;

                // Log the bonus
                DB::table('matching_bonus_logs')->insert([
                    'user_id' => $user->id,
                    'matched_pv' => $matchedPv,
                    'amount' => $bonusAmount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $user->save();
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Matching bonus distributed.']);
    }


}
