# Changes of main to here

This is all the changes I made between the main program and the new celebrities html


### sgame.html
- 16: <link rel="stylesheet" href="../font-awesome-4.7.0/css/font-awesome.min.css">
- 66: fetch('sget_data.php', {
- 359: document.getElementById('end_date').textContent = date_var.substring(13,date_var.length);
- 1206: <script type="text/javascript" src="sevents.js"></script>
- 1207: <script type="text/javascript" src="slinks.js"></script>
- 1422: document.getElementById('end_date').textContent = date.textContent.substring(13,date.textContent.length);
- 1901: xhr.open("POST", "sinsert_data.php", true);
- 1929: fetch('cget_data.php', {

Replaces:
- getCookie("s
    - 47 times
- setCookie("s
    - 15 times

### sevents.js
- Same

### slinks.js
- Same

### sinsert_data.php
- 63: $stmt = $conn->prepare("SELECT id FROM sevents WHERE date = ? ORDER BY id DESC LIMIT 1");
- 98: $stmt = $conn->prepare("INSERT INTO sevents (id, num_guesses, passed, date) VALUES (?, ?, ?, ?)");

### sget_data.php
- $sql = "SELECT num_guesses FROM sevents WHERE date = ?";



