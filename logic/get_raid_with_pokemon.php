<?php
/**
 * Get raid data with pokemon.
 * @param $raid_id
 * @return array
 */
function get_raid_with_pokemon($raid_id)
{
    // Remove all non-numeric characters
    $raidid = preg_replace( '/[^0-9]/', '', $raid_id );

    // Get the raid data by id.
    $rs = my_query(
        "
        SELECT     raids.*,
                   gyms.lat, gyms.lon, gyms.address, gyms.gym_name, gyms.ex_gym, gyms.gym_note, gyms.gym_id, gyms.img_url,
                   pokemon.pokedex_id, pokemon.pokemon_name, pokemon.pokemon_form, pokemon.raid_level, pokemon.min_cp, pokemon.max_cp, pokemon.min_weather_cp, pokemon.max_weather_cp, pokemon.weather, pokemon.shiny,
                   users.name,
                   TIME_FORMAT(TIMEDIFF(end_time, UTC_TIMESTAMP()) + INTERVAL 1 MINUTE, '%k:%i') AS t_left,
                   TIMESTAMPDIFF(MINUTE,raids.start_time,raids.end_time) as t_duration
        FROM       raids
        LEFT JOIN  gyms
        ON         raids.gym_id = gyms.id
        LEFT JOIN  pokemon
        ON         raids.pokemon = CONCAT(pokemon.pokedex_id, '-', pokemon.pokemon_form)
        LEFT JOIN  users
        ON         raids.user_id = users.user_id
        WHERE      raids.id = {$raidid}
        "
    );

    // Get the row.
    $raid = $rs->fetch_assoc();

    debug_log($raid);

    return $raid;
}

?>
