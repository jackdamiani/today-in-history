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




To see words that are set up for a sertain category_id:
SELECT w.word FROM `word_category` AS wc JOIN words w ON w.id = wc.word_id WHERE category_id = 9 LIMIT 100 

SELECT w.word 
FROM word_category AS wc
JOIN words AS w ON w.id = wc.word_id
JOIN categories AS c ON c.id = wc.category_id
WHERE c.category = 'palindrome'
LIMIT 100;

Good ideas:
Words with No Repeated Letters:
Category Name: "Unique Letters Only"
Regex: ^(?!.*(.).*\1).*
Matches words where each letter is unique (e.g., "brick", "jumbo").

Words with More Vowels Than Consonants:
Category Name: "Vowel Heavy"
Regex: ^(?=(.*[aeiou]){5})(?=(.*[^aeiou]){0,4}).*$
Matches words with more vowels than consonants (e.g., "ouija", "idea").

Get the word in two categories:
SELECT *
FROM words w
JOIN word_category wc1 ON w.id = wc1.word_id
JOIN categories c1 ON wc1.category_id = c1.id AND c1.category = "CATEGORY"
JOIN word_category wc2 ON w.id = wc2.word_id
JOIN categories c2 ON wc2.category_id = c2.id AND c2.category = "CATEGORY2"
WHERE w.word = "WORD";