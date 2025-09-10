<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fund;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\FundBonusHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FundDistribution extends Controller
{
    public function index()
    {
        $funds = Fund::where('status', 1)->first();
        return view('admin.pages.funds.index', compact('funds'));
    }
    public function distributeClubFund()
    {
        $clubFund = Fund::where('name', 'Club Fund')->where('status', 1)->first();

        if (!$clubFund || $clubFund->amount <= 0) {
            return redirect()->back()->with('error', 'No fund available for distribution.');
        }

        $clubMembers = User::where('is_club', 1)->get();

        if ($clubMembers->isEmpty()) {
            return redirect()->back()->with('error', 'No club members found.');
        }

        $memberCount = $clubMembers->count();
        $amountPerMember = round($clubFund->amount / $memberCount, 2);

        DB::transaction(function() use ($clubMembers, $amountPerMember, $clubFund) {
            foreach ($clubMembers as $member) {
                $member->increment('income_wallet', $amountPerMember);

                FundBonusHistory::create([
                    'user_id' => $member->id,
                    'fund_name' => $clubFund->name,
                    'amount' => $amountPerMember,
                    'description' => 'Club Fund Distribution',
                ]);
            }

            $clubFund->amount = 0;
            $clubFund->save();
        });

        return redirect()->back()->with('success', "Club fund distributed successfully to {$memberCount} members.");
    }
}
