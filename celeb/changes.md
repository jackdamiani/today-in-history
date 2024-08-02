# Changes of main to here

This is all the changes I made between the main program and the new celebrities html


### cgame.html
- 66: fetch('cget_data.php', {
- 1206: <script type="text/javascript" src="cevents.js"></script>
- 1901: xhr.open("POST", "cinsert_data.php", true);
- 1929: fetch('cget_data.php', {

Replaces:
- getCookie("c
    - 47 times
- setCookie("c
    - 15 times

### cevents.js
- Same

### clinks.js
- Same

### cinsert_data.php
- 63: $stmt = $conn->prepare("SELECT id FROM cevents WHERE date = ? ORDER BY id DESC LIMIT 1");
- 98: $stmt = $conn->prepare("INSERT INTO cevents (id, num_guesses, passed, date) VALUES (?, ?, ?, ?)");

### cget_data.php
- $sql = "SELECT num_guesses FROM cevents WHERE date = ?";



