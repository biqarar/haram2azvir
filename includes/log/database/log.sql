
#2018-09-13 22:09:01 --- 21 ms | ----- http://haram2azvir.local/transfer?level=teacher
SELECT person.* FROM person INNER JOIN users_branch ON users_branch.users_id = person.users_id WHERE users_branch.type = 'teacher'
#2018-09-13 22:09:53 --- 14 ms | ----- http://haram2azvir.local/transfer?level=operator
SELECT person.* FROM person INNER JOIN users_branch ON users_branch.users_id = person.users_id WHERE users_branch.type = 'operator'