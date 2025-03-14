List of prompts to help generate events:

I'm going to give you a bunch of records. I only need them if they have the month, day, and year of the record broken. If there are two dates, only use the second one. If it reaches that condition, I need it in the format:

{"DATE": {"YEAR": "Name breaks NHL XXX record with XXX", ... }, ...}

The dates should be 4 digits and sorted in order from 0101 to 1231, and if there are multiple from the same day, they should be in order too.

Example:
Most touchdowns, game: 6, Alvin Kamara, December 25, 2020

Should be:
{"1225": {"2020": "Alvin Kamara breaks the NHL record most touchdowns in a game with 6"}}

Records are:

-------------------------------------------------------------------------------------------
For Links:

OK I need wikipedia links for the following events in the following format:

"DATE": ["link", "link", "link"],

I am going to give you 3 events in the form:
"DATE": {"YEAR": "EVENT", "YEAR": "EVENT", "YEAR": "EVENT"}

I only need one wikipedia link per event. Ignore the year component

Example:
Events are:
"1001": {"1661": "Yachting begins in England", "1800": "Spain cedes Louisiana to France in a secret treaty", "1975": "Muhammad Ali beats Joe Frazier in the Thrilla in Manilla"},

Output should be:
"1001": ["https://en.wikipedia.org/wiki/Yachting", "https://en.wikipedia.org/wiki/Third_Treaty_of_San_Ildefonso", "https://en.wikipedia.org/wiki/Thrilla_in_Manila"],

Here are the events:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


Maybe need:
That is not in the right format. Ignore the years and write it as 

"1001": ["LINK", "LINK", "LINK"]


-----------------------------------------------------------------------------

I'm going to give you a bunch of records. I only need them if they have the month, day, and year of the record broken. If there are two dates, only use the second one. If it reaches that condition, I need it in the format:

{"DATE": {"YEAR": "EVENT", ... }, ...} 

The dates should be 4 digits and sorted in order from 0101 to 1231, and if there are multiple from the same day, they should be in order too.

Example:


Most touchdowns, game: 6, Alvin Kamara, December 25, 2020

Should be:
{"1225": {"2020": "Alvin Kamara breaks the NHL record most touchdowns in a game with 6"}}

Records are from october 19:

You can put them all in one group like { "1019": { "YEAR": "EVENT", "YEAR": "EVENT", etc...}}. Order the events in order by year.


--------------------------------------------------------------------------------


I need you to generate a structured output of celebrity names and their birthdays.

I need two weeks worth of dates. 

I need them in the format:

{
    "MMDD": {
        "YYYY": "NAME (Something they're famous for),
        "YYYY": "NAME (Something they're famous for),
        "YYYY": "NAME (Something they're famous for),
        "YYYY": "NAME (Something they're famous for),
        "YYYY": "NAME (Something they're famous for),
        "YYYY": "NAME (Something they're famous for)
    },
    "MMDD": {
        "YYYY": "NAME (Something they're famous for),
        "YYYY": "NAME (Something they're famous for),
        "YYYY": "NAME (Something they're famous for),
        "YYYY": "NAME (Something they're famous for),
        "YYYY": "NAME (Something they're famous for),
        "YYYY": "NAME (Something they're famous for)
    }, 

    ...
}

I need the dates to start at 0101 (january first) and go on from there.
I need the YYYY to be in order, and I need 6 per date.

Also make sure that the years are never before 1935 and there is at least one or two from the 1990s or 2000s


--------------------------------------------------------------------------

I'm going to give you an article with records. I only need them if they have the month, day, and year of the record broken. If there are two dates, only use the second one. If it reaches that condition, I need it in the format: 

{"DATE": {"YEAR": "EVENT", ... }, ...} 

The dates should be 4 digits and sorted in order from 0101 to 1231, and if there are multiple from the same day, they should be in order too.

Example:


Most touchdowns, game: 6, Alvin Kamara, December 25, 2020

Should be:
{"1225": {"2020": "Alvin Kamara breaks the NHL record most touchdowns in a game with 6"}}

Records are from february 16: 

You can put them all in one group like { "1019": { "YEAR": "EVENT", "YEAR": "EVENT", etc...}}. Make sure to Order the events in order by year.

The article will come on the next prompt. Any questions?