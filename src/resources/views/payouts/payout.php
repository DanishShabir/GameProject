<!DOCTYPE html>
<html lang="en">

<form action="payouts" method="post">
    <div>
        <label>Jackpot target</label>
        <input type="text" name="jackpot_target" value="50000" required>
    </div>
    <div>
        <label>End Pot value</label>
        <input type="text" name="end_pot_value" value="500000" required>
    </div>
    <div>
        <label>Total number of game players</label>
        <input type="text" name="number_of_players" value="50" required>
    </div>
    <div>
        <label>Max number of winners</label>
        <input type="text" name="number_of_winners" value="30" required>
    </div>
    
    <div>
        <label>Admin fee percent</label>
        <input type="text" name="admin_fee_percent" value="30" required>
    </div>
    
    <div>
        <input type="submit" name="submit">
    </div>
</form>