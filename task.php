<?php
require_once 'MyLogger.php';
require_once 'PDOAdapter.php';
$login = 'user';
$pwd = 12344321;
$dsn = "mysql:host=localhost;dbname=test";

$pdo = new PDOAdapter($dsn, $login, $pwd, 'errorLogger.txt');

//Написать запросы к базе данных, результат представить в виде самой простой HTML-страницы. Подробно:
//Используя SQL и компонент PDOAdapter:
//1) Определить максимальный возраст
$sql = "SELECT MAX(age) AS age FROM person;";
$res1 = $pdo->execute('selectOne', $sql);
//var_dump($res1);

//2) Найти любую персону, у которой mother_id не задан и возраст меньше максимального
$sql = "SELECT lastname, firstname FROM person WHERE mother_id IS NULL AND age NOT IN(SELECT MAX(age) FROM person) LIMIT 1;";
$res2 = $pdo->execute('selectAll', $sql);
//var_dump($res2);
//3) изменить у нее возраст на максимальный
$sql = "UPDATE person SET age = 46  WHERE id = 1;";
$res3 = $pdo->execute('execute', $sql);
//var_dump($res3);
//4) Получить список персон максимального возраста (фамилия, имя). Желательно НЕ ИСПОЛЬЗУЯ полученное на шаге 1 значение.
$sql = "SELECT lastname, firstname  FROM person WHERE age > (SELECT AVG(age) FROM person) AND age NOT IN(SELECT MAX(age) AS age FROM person) ORDER BY lastname ASC;";
$res4 = $pdo->execute('selectAll', $sql);
//var_dump($res4);
//5) Сформировать и отобразить HTML-страницу:
//Заголовок "Список персон максимального возраста (здесь значение п.1)"
//Таблица, содержащая колонки: фамилия, имя, возраст.
//В строках таблицы - персоны, упорядоченные по возрастанию фамилии и имени.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TestTask</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<p>Максимальный возраст: <? foreach ($res1 as $value) echo $value; ?></p>
<p>Персона, у которой mother_id не задан и возраст меньше максимального: <? foreach ($res2 as $value) {foreach ($value as $key => $val){echo $val.' ';}}; ?></p>
    <table border="1px solid black">
        <tr><th>"Список персон максимального возраста"</th></tr>
        <? foreach ($res4 as $value){foreach ($value as $key => $val) {  ?>
        <tr>
            <? echo '<td>'.$val.'</td>'; ?>
        </tr>
        <? }} ?>

    </table>
</body>
</html>
