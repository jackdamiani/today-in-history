List of prompts to help generate events:

I'm going to give you a bunch of records. I only need them if they have the month, day, and year of the record broken. If there are two dates, only use the second one. If it reaches that condition, I need it in the format:

{"DATE": {"YEAR": "Name breaks NHL XXX record with XXX", ... }, ...}

The dates should be 4 digits and sorted in order from 0101 to 1231, and if there are multiple from the same day, they should be in order too.

Example:
Most touchdowns, game: 6, Alvin Kamara, December 25, 2020

Should be:
{"1225": {"2020": "Alvin Kamara breaks the NHL record most touchdowns in a game with 6"}}

Records are: