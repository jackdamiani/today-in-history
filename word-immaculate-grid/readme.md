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
Verbs = 


categories
1 noun
2 verb
3 adjective
4 adverb
5 preposition
6 conjunction
7 pronoun
8 interjection