function post_play_display(display){
    let display_ = ''
    let count = 0
    // Changed to letters then back to emojis
    // Code W=white, B=black, L=blue, R=red, O=orange, N=brown, G=green
    
    
    for (let i = 0; i < display.length; i++) {
        if (display[i] == 'W'){
            display_ += String.fromCodePoint(11036)
        }
        else if (display[i] == 'B'){
            display_ += String.fromCodePoint(11035)
        }
        else if (display[i] == 'L'){
            display_ += String.fromCodePoint(128998)
        }
        else if (display[i] == 'R'){
            display_ += String.fromCodePoint(128997)
        }
        else if (display[i] == 'O'){
            display_ += String.fromCodePoint(128999)
        }
        else if (display[i] == 'N'){
            display_ += String.fromCodePoint(129003)
        }
        else if (display[i] == 'G'){
            display_ += String.fromCodePoint(129001)
        }

        // if(display.slice(i,i+1) == String.fromCodePoint(11035)){
        //     display_ += display.slice(i,i+1)
        // }
        // else{
        //     display_ += display.slice(i,i+2)
        //     i++
        // }
        
        count += 1
        if (count % 3 == 0){
            display_ += '\n'
        }
        
    }
    return display_
}

var guess_number = 1;    

var output_grid = [
    [0,0,0],
    [0,0,0],
    [0,0,0],
    [0,0,0],
    [0,0,0],
    [0,0,0],
    [0,0,0],
    [0,0,0],
    [0,0,0],
    [0,0,0],
    [0,0,0],
    [0,0,0],
    [0,0,0],
    [0,0,0],
    [0,0,0]
]

setup()
function setup(){
    if (getCookie("played_today") == "True"){

        document.getElementById('year_input').disabled = true;
        let score = Number(getCookie("todays_score"))
        if (score == 16){
            document.getElementById('help_message').textContent = "You didn't get today's answer. Come back tomorrow for more!"
            document.getElementById('end_score').textContent = "X/15"
            document.getElementById('end_response').textContent = 'GAME COMPLETED'
        }
        else{
            document.getElementById('help_message').textContent = "You won today in " + score.toString() + " guesses. Come back tomorrow for more!"
            document.getElementById('end_score').textContent = getCookie("todays_score") + "/15"
            document.getElementById('end_response').textContent = 'YOU WON!'
        }
        document.getElementById('help_message').style.fontSize = "20px";

        
        add_histogram()
        
        //     <p id="end_date"></p>
        // <p id="end_score"></p>
        // <p id="correct_answer"></p>
        // <p id="share_text" style="white-space: pre-line;"></p>

        
        
        let avg_score = parseAvgScore("score_arr")
        if (typeof avg_score == Number){
            document.getElementById('avg_score').textContent = 'Average Score = ' + avg_score.toFixed(2).toString()
        }
        else{
            document.getElementById('avg_score').textContent = 'Average Score = ' + avg_score.toString()
        }

        let total_games_played = Number(getCookie("total_games"))
        document.getElementById('games_played').textContent = 'Games Played = ' + total_games_played
        
        var win_percentage = (Number(getCookie("win_games")) / Number(getCookie("total_games")) * 100).toFixed(1) 
        document.getElementById('win_percentage').textContent = 'Win Percentage = ' + win_percentage + '%'

        var display_share_text_post = getCookie("display_share_text_cookie")
        display_share_text_post_post = post_play_display(display_share_text_post)
        document.getElementById("share_text").textContent = display_share_text_post_post

        let cont_streak = parseStreakArr("streak_arr")
        let cont_streak_today = parseStreakArrToday("streak_arr")

        if (cont_streak || cont_streak_today){
            len_streak = parseStreakLen('streak_arr')
            let historyEmojis = [
                "0x1F5FF", // "🗿", Moai
                "128220", // "📜", Scroll
                "0x1F3FA", // "🏺", Amphora
                "0x2694", // "⚔️", Crossed Swords
                "127963", //"🏛️", Classical Building
                "0x26EA", // "⛪", Church
                "0x1F4D6", // "📖", Open Book
                "0x1F50D", // "🔍"  Magnifying Glass Tilted Right
                "0x1F30D", // "🌍", Globe Showing Europe-Africa
                "0x1F30E", // 🌎 Globe Showing Americas
                "0x1F30F", // 🌏 Globe Showing Asia-Australia
                "0x1F310",  // 🌐 Globe with Meridians
                "0x1F311", // 🌑 New Moon
                "0x1F31A", // 🌚 New Moon Face
                "0x1F312", // 🌒 Waxing Crescent Moon
                "0x1F313", // 🌓 First Quarter Moon
                "0x1F31B", // 🌛 First Quarter Moon Face
                "0x1F314", // 🌔 Waxing Gibbous Moon
                "0x1F315", // 🌕 Full Moon
                "0x1F31D", // 🌝 Full Moon with Face
                "0x1F316", // 🌖 Waning Gibbous Moon
                "0x1F317", // 🌗 Last Quarter Moon
                "0x1F31C", // 🌜 Last Quarter Moon Face
                "0x1F318", // 🌘 Waning Crescent Moon
                "0x1F319", // 🌙 Crescent Moon
                "0x1F3F0",  // 🏰 Castle
                "11088", // ⭐ Star
                "127942", // 🏆 Trophy


            ];

            let streak_text = `Streak: ${len_streak} `
            for (let i = 0; i < len_streak; i++){
                if (i == 49 || i == 149){
                    streak_text += String.fromCodePoint(historyEmojis[historyEmojis.length - 2]);
                    continue;
                }
                if (i == 99 || i == 199){
                    streak_text += String.fromCodePoint(historyEmojis[historyEmojis.length - 1]);
                    continue;
                }
                let j = i % (historyEmojis.length - 2);
                streak_text += String.fromCodePoint(historyEmojis[j])
            }
            document.getElementById("streak").textContent = streak_text
            document.getElementById("longest_streak").textContent = `Longest Streak = ${getCookie("longest_streak")}`
        }
        
        // document.getElementById("share_button").style.visibility = 'hidden';
        // document.getElementById('end_response').textContent = 'Game Complete!'
    }
    // Code to work with future dates
    var find_today = new Date();
    var dd = String(find_today.getDate()).padStart(2, '0');
    var mm = String(find_today.getMonth()+1).padStart(2, '0'); //January is 0!
    var yyyy = find_today.getFullYear();

    header_color = document.getElementById("top-header");
    footer_color = document.getElementById("bottom-footer");

    header_color.style.backgroundColor= 'lightblue';
    footer_color.style.backgroundColor= 'lightblue';

    setCookie("animation_1", "False", 0, 2)
    setCookie("animation_2", "False", 0, 2)
    setCookie("animation_3", "False", 0, 2)
    

    // // Actual Code
    // var find_today = new Date();
    // var dd = String(find_today.getDate()).padStart(2, '0');
    // var mm = String(find_today.getMonth() + 1).padStart(2, '0'); //January is 0!
    // var yyyy = find_today.getFullYear();

    todays_date = mm + dd
    var month_dict = {'01':'January', '02': 'February', '03':'March', '04':'April', '05':'May', '06':'June', '07':'July', '08':'August', '09':'September', '10':'October', '11':'November', '12':'December'}
    var date_ending = 'th'

    date = document.getElementById("date-header")
    if (dd<10){
        if (dd % 10 == 1){
            date_ending = 'st'
        }
        else if (dd % 10 == 2){
            date_ending = 'nd'
        }
        else if (dd % 10 == 3){
            date_ending = 'rd'
        }

        date.textContent = "TODAY IN HISTORY - " + month_dict[mm] + " " + dd[1] + date_ending;
    }
    else if(dd>20){
        if (dd % 10 == 1){
            date_ending = 'st'
        }
        else if (dd % 10 == 2){
            date_ending = 'nd'
        }
        else if (dd % 10 == 3){
            date_ending = 'rd'
        }
        date.textContent = "TODAY IN HISTORY - " + month_dict[mm] + " " + dd + date_ending;
    }
    else{
        date.textContent = "TODAY IN HISTORY - " + month_dict[mm] + " " + dd + date_ending;
    }
    

    var today = today_in_history[todays_date]
    today_keys = Object.keys(today)

    year1 = ""
    fact1 = ""
    year2 = ""
    fact2 = ""
    year3 = ""
    fact3 = ""

    
    for (let i = 0; i < today_keys.length; i++){
        if (i==0){
            year1 = today_keys[i]
            fact1 = today[today_keys[i]]
            console.log(year1, fact1)
        }
        else if (i==1){
            year2 = today_keys[i]
            fact2 = today[today_keys[i]]
            console.log(year2, fact2)
        }
        else if (i==2){
            year3 = today_keys[i]
            fact3 = today[today_keys[i]]
            console.log(year3, fact3)
        }
    }

    if (getCookie("played_today") == "True"){


        var link_dates = links[todays_date]
        if (link_dates[0] != "")
        {
            document.getElementById('correct_answer').innerHTML = "<a target=_blank href=" + link_dates[0] + ">" + year1 + " </a>"
        }
        else
            document.getElementById('correct_answer').innerHTML = year1 + " "
        if (link_dates[1] != "")
        {
            document.getElementById('correct_answer2').innerHTML = "<a target=_blank href=" + link_dates[1] + ">" + year2 + " </a>"
        }
        else
            document.getElementById('correct_answer2').innerHTML = year2 + " "
        if (link_dates[2] != "")
        {
            document.getElementById('correct_answer3').innerHTML = "<a target=_blank href=" + link_dates[2] + ">" + year3 + " </a>"
        }
        else
            document.getElementById('correct_answer3').innerHTML = year3 + " "
        // document.getElementById('correct_answer').textContent = 'Answer: ' + year1 + ' ' + year2 + ' ' + year3;
        document.getElementById('end_date').textContent = date.textContent.substring(18,date.textContent.length);
    }

    if (getCookie("game_started") == "True"){
        var guess_arr = parseGuessArr()
        for (let i = 0; i < guess_arr.length; i++){
            guess_button_onclick(guess_arr[i])
        }
    }

    let cont_streak = parseStreakArr("streak_arr")
    let cont_streak_today = parseStreakArrToday("streak_arr")
    if (getCookie('streak_arr') != "" && (cont_streak || cont_streak_today)){
        len_streak = parseStreakLen('streak_arr')
        let historyEmojis = [
            "0x1F5FF", // "🗿", Moai
            "128220", // "📜", Scroll
            "0x1F3FA", // "🏺", Amphora
            "0x2694", // "⚔️", Crossed Swords
            "127963", //"🏛️", Classical Building
            "0x26EA", // "⛪", Church
            "0x1F4D6", // "📖", Open Book
            "0x1F50D", // "🔍"  Magnifying Glass Tilted Right
            "0x1F30D", // "🌍", Globe Showing Europe-Africa
            "0x1F30E", // 🌎 Globe Showing Americas
            "0x1F30F", // 🌏 Globe Showing Asia-Australia
            "0x1F310",  // 🌐 Globe with Meridians
            "0x1F311", // 🌑 New Moon
            "0x1F31A", // 🌚 New Moon Face
            "0x1F312", // 🌒 Waxing Crescent Moon
            "0x1F313", // 🌓 First Quarter Moon
            "0x1F31B", // 🌛 First Quarter Moon Face
            "0x1F314", // 🌔 Waxing Gibbous Moon
            "0x1F315", // 🌕 Full Moon
            "0x1F31D", // 🌝 Full Moon with Face
            "0x1F316", // 🌖 Waning Gibbous Moon
            "0x1F317", // 🌗 Last Quarter Moon
            "0x1F31C", // 🌜 Last Quarter Moon Face
            "0x1F318", // 🌘 Waning Crescent Moon
            "0x1F319", // 🌙 Crescent Moon
            "0x1F3F0",  // 🏰 Castle
            "11088", // ⭐ Star
            "127942", // 🏆 Trophy


        ];

        let streak_text = `Streak: ${len_streak} `
        for (let i = 0; i < len_streak; i++){
            if (i == 49 || i == 149){
                streak_text += String.fromCodePoint(historyEmojis[historyEmojis.length - 2]);
                continue;
            }
            if (i == 99 || i == 199){
                streak_text += String.fromCodePoint(historyEmojis[historyEmojis.length - 1]);
                continue;
            }
            let j = i % (historyEmojis.length - 2);
            streak_text += String.fromCodePoint(historyEmojis[j])
        }

        document.getElementById("streak_home").textContent = streak_text
    }
}


fact1_obj = document.getElementById('fact1');
fact1_obj.innerHTML = fact1;

fact2_obj = document.getElementById('fact2');
fact2_obj.textContent = fact2;

fact3_obj = document.getElementById('fact3');
fact3_obj.textContent = fact3;

if(getCookie("game_started") == "True"){
guessArray = parseGuessArr()
}


$(window).load(function () {
$(".trigger_help_menu").click(function(){
    $('.help_menu').show();
});
$(".stats").click(function(){
    $('.end_menu').show();
    document.getElementsByClassName('end_menu')[0].style.visibility = 'visible'
});
$('.help_menu').click(function(){
    $('.help_menu').hide();
    // $('.end_menu').hide();
});
$('.end_menu').click(function(){
    $('.end_menu').hide();
});
$('.popupCloseButton').click(function(){
    $('.help_menu').hide();
    $('.end_menu').hide();
});
});


// Funtion for getting cookies
function getCookie(cname) {
let name = cname + "=";
let decodedCookie = decodeURIComponent(document.cookie);
let ca = decodedCookie.split(';');
for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
    c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
    return c.substring(name.length, c.length);
    }
}
return "";
}

// function for setting cookies
function setCookie(cname, cvalue, exdays, exp=0) {
// sets for future days
if (exp == 1){
    const d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
// sets cookie until midnight
else if (exp == 2){
    const d = new Date();
    let year = d.getFullYear()
    let month = d.getMonth()
    let day = d.getDate()

    const d_midnight = new Date(year, month, day, 23, 59, 59, 59)
    let expires = "expires="+d_midnight.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";

}
else {
    const d = new Date("2999-12-31");
    let expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
}

function addNumGames(){
if (getCookie("total_games") == ''){
    setCookie("total_games", "1", 0, 0)
}
else{
    setCookie("total_games", (Number(getCookie("total_games"))+1).toString(), 0, 0) 
}
}

function addWinGames(){
if (getCookie("win_games") == ''){
    setCookie("win_games", "1", 0, 0)
}
else{
    setCookie("win_games", (Number(getCookie("win_games"))+1).toString(), 0, 0) 
}
}
// document.cookie = "total_games=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
// document.cookie = "win_games=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";

console.log("Total Number Games", getCookie("total_games"));
console.log("Win Games", getCookie("win_games"))

console.log("Played Today", getCookie("played_today"))
console.log("Today's Score", getCookie("todays_score"))





function parseAvgScore(cname){
let arr_str = getCookie(cname)
let arr = arr_str.substring(1, arr_str.length-1)
arr = arr.replace(/['"]+/g, '')
score_vals = arr.split(',');
let running_sum = 0
for(let i = 0; i<score_vals.length; i++) {
    running_sum += Number(score_vals[i])
}
avg_score = running_sum / score_vals.length
return avg_score.toFixed(2)
}

function parseStreakArr(cname){
let arr_str = getCookie(cname)
let arr = arr_str.substring(1, arr_str.length-1)
arr = arr.replace(/['"]+/g, '')
streak_vals = arr.split(',');

console.log("streak value beginning", streak_vals)
console.log(streak_vals[streak_vals.length - 1])

let last_day = streak_vals[streak_vals.length - 1]

let today = new Date();

// Subtract one day (in milliseconds)
today.setDate(today.getDate() - 1);

// Format the date as needed
let year = today.getFullYear();
let month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
let day = String(today.getDate()).padStart(2, '0');

let yesterday = `${year}-${month}-${day}`;

console.log(yesterday); // Output: (e.g., "2024-08-11")

return yesterday == last_day
}

function parseStreakArrToday(cname) {
let arr_str = getCookie(cname);
let arr = arr_str.substring(1, arr_str.length - 1);
arr = arr.replace(/['"]+/g, '');
let streak_vals = arr.split(',');

console.log("streak value beginning", streak_vals);
console.log(streak_vals[streak_vals.length - 1]);

let last_day = streak_vals[streak_vals.length - 1];

let today = new Date();

// Format today's date as needed (YYYY-MM-DD)
let year = today.getFullYear();
let month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
let day = String(today.getDate()).padStart(2, '0');

let today_ = `${year}-${month}-${day}`;

console.log(today_); // Output: (e.g., "2024-10-07")

// Check if today's date matches the last element in the streak array
return today_ == last_day;
}

function parseStreakLen(cname){
let arr_str = getCookie(cname)
let arr = arr_str.substring(1, arr_str.length-1)
arr = arr.replace(/['"]+/g, '')
streak_vals = arr.split(',');

return streak_vals.length

}

function parseGuessArr(){
guess_arr_temp = getCookie("guess_arr");
guess_arr_temp = guess_arr_temp.replace(/[\[\]]/g, '').replace(/['"]+/g, '');
let guessArraystr = guess_arr_temp.split(',');
let guessArray = guessArraystr.map(Number);

return guessArray;
}


function fire_fucking_works(guess){

if (guess >= 10){
    var click_limit = 200;
    var firework_limit = 20 * (guess - 10) + 50;
    var particle_number = 200;
}
else if (guess == 9){
    var click_limit = 100;
    var firework_limit = 45;
    var particle_number = 500
}
else if (guess == 8){
    var click_limit = 100;
    var firework_limit = 35;
    var particle_number = 500
}
else if (guess == 7){
    var click_limit = 50;
    var firework_limit = 25;
    var particle_number = 500
}
else if (guess == 6){
    var click_limit = 50;
    var firework_limit = 15;
    var particle_number = 500
}
else if (guess == 5){
    var click_limit = 25;
    var firework_limit = 8;
    var particle_number = 500
}
else if (guess == 4){
    var click_limit = 10;
    var firework_limit = 6;
    var particle_number = 500
}
else if (guess == 3){
    var click_limit = 2;
    var firework_limit = 2;
    var particle_number = 500
}


// when animating on canvas, it is best to use requestAnimationFrame instead of setTimeout or setInterval
// not supported in all browsers though and sometimes needs a prefix, so we need a shim
window.requestAnimFrame = ( function() {
    return window.requestAnimationFrame ||
                window.webkitRequestAnimationFrame ||
                window.mozRequestAnimationFrame ||
                function( callback ) {
                    window.setTimeout( callback, 1000 / 60 );
                };
})();

// now we will setup our basic variables for the demo
try{
    var canvas = document.getElementById( 'fireworkCanvas' ),
        ctx = canvas.getContext( '2d' ),
        // full screen dimensions
        cw = window.innerWidth,
        ch = window.innerHeight,
        // firework collection
        fireworks = [],
        // particle collection
        particles = [],
        // starting hue
        hue = 120,
        // when launching fireworks with a click, too many get launched at once without a limiter, one launch per 5 loop ticks
        limiterTotal = click_limit,
        limiterTick = 0,
        // this will time the auto launches of fireworks, one launch per 80 loop ticks
        timerTotal = firework_limit,
        timerTick = 0,
        mousedown = false,
        // mouse x coordinate,
        mx,
        // mouse y coordinate
        my;
} catch (error) {
    console.error('An error occurred:', error);
    // Log the error or perform other error-handling actions
}


        
// set canvas dimensions
canvas.width = cw;
canvas.height = ch;

// now we are going to setup our function placeholders for the entire demo

// get a random number within a range
function random( min, max ) {
    return Math.random() * ( max - min ) + min;
}

// calculate the distance between two points
function calculateDistance( p1x, p1y, p2x, p2y ) {
    var xDistance = p1x - p2x,
            yDistance = p1y - p2y;
    return Math.sqrt( Math.pow( xDistance, 2 ) + Math.pow( yDistance, 2 ) );
}

// create firework
function Firework( sx, sy, tx, ty ) {
    // actual coordinates
    this.x = sx;
    this.y = sy;
    // starting coordinates
    this.sx = sx;
    this.sy = sy;
    // target coordinates
    this.tx = tx;
    this.ty = ty;
    // distance from starting point to target
    this.distanceToTarget = calculateDistance( sx, sy, tx, ty );
    this.distanceTraveled = 0;
    // track the past coordinates of each firework to create a trail effect, increase the coordinate count to create more prominent trails
    this.coordinates = [];
    this.coordinateCount = 3;
    // populate initial coordinate collection with the current coordinates
    while( this.coordinateCount-- ) {
        this.coordinates.push( [ this.x, this.y ] );
    }
    this.angle = Math.atan2( ty - sy, tx - sx );
    this.speed = 2;
    this.acceleration = 1.05;
    this.brightness = random( 50, 70 );
    // circle target indicator radius
    this.targetRadius = 1;
}

// update firework
Firework.prototype.update = function( index ) {
    // remove last item in coordinates array
    this.coordinates.pop();
    // add current coordinates to the start of the array
    this.coordinates.unshift( [ this.x, this.y ] );
    
    // cycle the circle target indicator radius
    if( this.targetRadius < 8 ) {
        this.targetRadius += 0.3;
    } else {
        this.targetRadius = 1;
    }
    
    // speed up the firework
    this.speed *= this.acceleration;
    
    // get the current velocities based on angle and speed
    var vx = Math.cos( this.angle ) * this.speed,
            vy = Math.sin( this.angle ) * this.speed;
    // how far will the firework have traveled with velocities applied?
    this.distanceTraveled = calculateDistance( this.sx, this.sy, this.x + vx, this.y + vy );
    
    // if the distance traveled, including velocities, is greater than the initial distance to the target, then the target has been reached
    if( this.distanceTraveled >= this.distanceToTarget ) {
        createParticles( this.tx, this.ty );
        // remove the firework, use the index passed into the update function to determine which to remove
        fireworks.splice( index, 1 );
    } else {
        // target not reached, keep traveling
        this.x += vx;
        this.y += vy;
    }
}

// draw firework
Firework.prototype.draw = function() {
    ctx.beginPath();
    // move to the last tracked coordinate in the set, then draw a line to the current x and y
    ctx.moveTo( this.coordinates[ this.coordinates.length - 1][ 0 ], this.coordinates[ this.coordinates.length - 1][ 1 ] );
    ctx.lineTo( this.x, this.y );
    ctx.strokeStyle = 'hsl(' + hue + ', 100%, ' + this.brightness + '%)';
    ctx.stroke();
    
    ctx.beginPath();
    // draw the target for this firework with a pulsing circle
    ctx.arc( this.tx, this.ty, this.targetRadius, 0, Math.PI * 2 );
    ctx.stroke();
}

// create particle
function Particle( x, y ) {
    this.x = x;
    this.y = y;
    // track the past coordinates of each particle to create a trail effect, increase the coordinate count to create more prominent trails
    this.coordinates = [];
    this.coordinateCount = 5;
    while( this.coordinateCount-- ) {
        this.coordinates.push( [ this.x, this.y ] );
    }
    // set a random angle in all possible directions, in radians
    this.angle = random( 0, Math.PI * 2 );
    this.speed = random( 1, 10 );
    // friction will slow the particle down
    this.friction = 0.95;
    // gravity will be applied and pull the particle down
    this.gravity = 1;
    // set the hue to a random number +-50 of the overall hue variable
    this.hue = random( hue - 50, hue + 50 );
    this.brightness = random( 50, 80 );
    this.alpha = 1;
    // set how fast the particle fades out
    this.decay = random( 0.015, 0.03 );
}

// update particle
Particle.prototype.update = function( index ) {
    // remove last item in coordinates array
    this.coordinates.pop();
    // add current coordinates to the start of the array
    this.coordinates.unshift( [ this.x, this.y ] );
    // slow down the particle
    this.speed *= this.friction;
    // apply velocity
    this.x += Math.cos( this.angle ) * this.speed;
    this.y += Math.sin( this.angle ) * this.speed + this.gravity;
    // fade out the particle
    this.alpha -= this.decay;
    
    // remove the particle once the alpha is low enough, based on the passed in index
    if( this.alpha <= this.decay ) {
        particles.splice( index, 1 );
    }
}

// draw particle
Particle.prototype.draw = function() {
    ctx. beginPath();
    // move to the last tracked coordinates in the set, then draw a line to the current x and y
    ctx.moveTo( this.coordinates[ this.coordinates.length - 1 ][ 0 ], this.coordinates[ this.coordinates.length - 1 ][ 1 ] );
    ctx.lineTo( this.x, this.y );
    ctx.strokeStyle = 'hsla(' + this.hue + ', 100%, ' + this.brightness + '%, ' + this.alpha + ')';
    ctx.stroke();
}

// create particle group/explosion
function createParticles( x, y ) {
    // increase the particle count for a bigger explosion, beware of the canvas performance hit with the increased particles though
    var particleCount = particle_number;
    while( particleCount-- ) {
        particles.push( new Particle( x, y ) );
    }
}

// main demo loop
function loop() {
    // this function will run endlessly with requestAnimationFrame
    requestAnimFrame( loop );
    
    // increase the hue to get different colored fireworks over time
    //hue += 0.5;

// create random color
hue= random(0, 360 );
    
    // normally, clearRect() would be used to clear the canvas
    // we want to create a trailing effect though
    // setting the composite operation to destination-out will allow us to clear the canvas at a specific opacity, rather than wiping it entirely
    ctx.globalCompositeOperation = 'destination-out';
    // decrease the alpha property to create more prominent trails
    ctx.fillStyle = 'rgba(0, 0, 0, 0.5)';
    ctx.fillRect( 0, 0, cw, ch );
    // change the composite operation back to our main mode
    // lighter creates bright highlight points as the fireworks and particles overlap each other
    ctx.globalCompositeOperation = 'lighter';
    
    // loop over each firework, draw it, update it
    var i = fireworks.length;
    while( i-- ) {
        fireworks[ i ].draw();
        fireworks[ i ].update( i );
    }
    
    // loop over each particle, draw it, update it
    var i = particles.length;
    while( i-- ) {
        particles[ i ].draw();
        particles[ i ].update( i );
    }
    
    // launch fireworks automatically to random coordinates, when the mouse isn't down
    if( timerTick >= timerTotal ) {
        if( !mousedown ) {
            // start the firework at the bottom middle of the screen, then set the random target coordinates, the random y coordinates will be set within the range of the top half of the screen
            fireworks.push( new Firework( cw / 2, ch, random( 0, cw ), random( 0, ch / 2 ) ) );
            timerTick = 0;
        }
    } else {
        timerTick++;
    }
    
    // limit the rate at which fireworks get launched when mouse is down
    if( limiterTick >= limiterTotal ) {
        if( mousedown ) {
            // start the firework at the bottom middle of the screen, then set the current mouse coordinates as the target
            fireworks.push( new Firework( cw / 2, ch, mx, my ) );
            limiterTick = 0;
        }
    } else {
        limiterTick++;
    }
}

// mouse event bindings
// update the mouse coordinates on mousemove
canvas.addEventListener( 'mousemove', function( e ) {
    mx = e.pageX - canvas.offsetLeft;
    my = e.pageY - canvas.offsetTop;
});

// toggle mousedown state and prevent canvas from being selected
canvas.addEventListener( 'mousedown', function( e ) {
    e.preventDefault();
    mousedown = true;
});

canvas.addEventListener( 'mouseup', function( e ) {
    e.preventDefault();
    mousedown = false;
});

// keep em going!
loop();
}

function insert_row_php(num_guess, utcDate) {
var xhr = new XMLHttpRequest();
xhr.open("POST", "insert_data.php", true);
xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
        // Handle response
        console.log("Successfully inserted entry")
    }
};

// Send request
xhr.send("num_guess=" + encodeURIComponent(num_guess)+ "&date=" + encodeURIComponent(utcDate));
}

function add_histogram() {
try {

var clientDate = new Date();
var utcDate = new Date(Date.UTC(
    clientDate.getFullYear(),
    clientDate.getMonth(),
    clientDate.getDate(),
    clientDate.getHours(),
    clientDate.getMinutes(),
    clientDate.getSeconds()
));
utcDate = utcDate.toISOString();

fetch('get_data.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: 'date=' + encodeURIComponent(utcDate)
})
.then(response => {
    if (!response.ok) {
        throw new Error('Network response was not ok ' + response.statusText);
    }
    return response.json();
})
.then(data => {
    console.log("Data received:", data);
    const ctx = document.getElementById('scoreHistogram').getContext('2d');

    ctx.strokeStyle = 'black';
    ctx.lineWidth = 5;
    ctx.strokeRect(0, 0, ctx.width, ctx.height);

    // Generate labels based on the range of num_guesses (e.g., 3 to 15)
    const labels = Array.from({ length: 13 }, (_, i) => i + 3);
    labels.push('X'); // Add 'X' at the end to represent 16

    // Count occurrences of each num_guesses value
    const counts = labels.map(label => {
        const num = label === 'X' ? 16 : label;
        return data.filter(n => n == num).length;
    });

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Players',
                data: counts,
                borderColor: '#000000',
                borderWidth: 1,
                backgroundColor: [
                    'rgba(0, 156, 52, 1)',    // Green solid
                    'rgba(0, 156, 52, 0.2)',  // Green 20% opacity
                    'rgba(150, 75, 0, 1)',    // Brown solid
                    'rgba(150, 75, 0, 0.3)',  // Brown 30% opacity
                    'rgba(255, 165, 1, 1)',   // Orange solid
                    'rgba(255, 165, 0, 0.3)', // Orange 30% opacity
                    'rgba(255, 0, 0, 1)',     // Red solid
                    'rgba(255, 0, 0, 0.2)',   // Red 20% opacity
                    'rgba(0, 0, 255, 1)',     // Blue solid
                    'rgba(0, 0, 255, 0.2)',   // Blue 20% opacity
                    'rgba(0, 0, 0, 1)',       // Black solid
                    'rgba(0, 0, 0, 0.5)',     // Black 50% opacity
                    'rgba(0, 0, 0, 0.2)',     // Black 20% opacity
                    'rgba(255, 255, 255, 1)'  // White
                ]
            }]
        },
        options: {
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        size: 10,
                        minRotation: 90,
                        autoSkip: false
                    }

                },
                y: {
                    grid: {
                        display: true
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    


    console.log("Histogram chart created successfully");
})
.catch(error => {
    console.error("Error fetching data:", error);
});

// Critical code here
} catch (error) {
    console.error('An error occurred:', error);
    // Log the error or perform other error-handling actions
}
}