categories
1 noun
2 verb
3 adjective
4 adverb
5 preposition
6 conjunction
7 pronoun
8 interjection

['n', 'pr', 'aj', 'v', 'av', 'i', 'c', 'pn']

n = noun
pr = preposition
adj = adjective
v = verb
av = adverb
aj = adjective
i = interjection
c = conjunction
pn = pronoun

excluded: p - participle?

SELECT category_id FROM word_category WHERE word_id=(SELECT id FROM `words` WHERE word='boat')

Guide:
Nouns = 63184
Verbs = 12499
Adjectives = 27367
Adverbs = 3854
Prepositions = 144
Conjunction = 63
Pronoun = 83
Interjection = 127 

Nouns and Verbs = 4948
Nouns and Adjectives = 
Nouns 

To see the words that are in two categories (change the 1 and 2)
SELECT w.id, w.word
FROM word_category wc
JOIN words w ON wc.word_id = w.id
WHERE wc.category_id IN (1, 2)
GROUP BY wc.word_id
HAVING COUNT(DISTINCT wc.category_id) = 2;


To see words that are set up for a sertain category_id:
SELECT w.word FROM `word_category` AS wc JOIN words w ON w.id = wc.word_id WHERE category_id = 9 LIMIT 100 