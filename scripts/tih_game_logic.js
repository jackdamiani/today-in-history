share_text_ = ''

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
document.cookie = "clientDate=" + encodeURIComponent(utcDate) + "; path=/";

function insert_square_stats(){

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
        const numPlayers = data.length;
        if(numPlayers == 0){
            const h1AS = document.getElementById('s2_4');
            h1AS.textContent = "You're the First Player!";
            console.log("First Player")

            const h1BS = document.getElementById('s4_4');
            h1BS.textContent = ":)";
        }
        else{
            const sum = data.reduce((accumulator, currentValue) => accumulator + currentValue, 0);
            const avgScore = (sum / numPlayers).toFixed(1);

            const h1AS = document.getElementById('s2_4');
            h1AS.textContent = avgScore;
            console.log("Today's Average", avgScore)

            const bestScore = data.reduce((min, currentValue) => Math.min(min, currentValue), Infinity);

            const h1BS = document.getElementById('s4_4');
            h1BS.textContent = bestScore;
            console.log("Today's Best Score", bestScore)

        }
    })
}


window.onload = function() {
    document.getElementById("end_id").style.visiblity = 'hidden';
    if(getCookie("played_today") != "True"){
        console.log('havent played today')
        document.getElementById("help_menu_id").style.visiblity = 'visible';
        document.getElementsByClassName("stats")[0].style.visibility = 'hidden';
    }
    else{
        console.log('played today')
        document.getElementsByClassName("help_menu")[0].style.visiblity = 'hidden';
        document.getElementsByClassName("stats")[0].style.visibility = 'visible';
        document.getElementById("help_menu_id").style.display = "none";
    }

    // writes in avg score
    const gridItem = document.getElementById('s2_4');
    insert_square_stats()
    gridItem.innerText = ''; 
}

function animateSquare(square_element, maxSize = 5, duration = 1000) {
    const square = document.getElementById(square_element);
    
    // Enlarge the square to a size based on viewport height (vh)
    square.style.width = `${maxSize}vh`;
    square.style.height = `${maxSize}vh`;
    
    // After the specified duration, return the square to its original size
    setTimeout(() => {
        square.style.width = '5vh';
        square.style.height = '5vh';
    }, duration);
}


function get_difference_time_guess(guess, year){
    return Math.abs(guess-year)
}

function set_colors(box_str, year_diff, year){
    // Black
    if (year_diff >= 1001){
        var opaque_value = 1001/year_diff
        var rgba_val = "rgba(0,0,0,".concat(opaque_value.toString(), ")")
        var box = document.getElementById(box_str).style.backgroundColor = rgba_val;
        return 1;
    }

    // Blue
    else if (year_diff >= 101){
        var opaque_value = 101/year_diff
        var rgba_val = "rgba(0,0,255,".concat(opaque_value.toString(), ")")
        var box = document.getElementById(box_str).style.backgroundColor = rgba_val;
        return 2;
    }

    // Red
    else if (year_diff >= 26){
        var opaque_value = 26/year_diff
        var rgba_val = "rgba(255,0,0,".concat(opaque_value.toString(), ")")
        var box = document.getElementById(box_str).style.backgroundColor = rgba_val;
        return 3;
    }

    // Orange
    else if (year_diff >= 11){
        var opaque_value = 11/year_diff
        var rgba_val = "rgba(255,165,0,".concat(opaque_value.toString(), ")")
        var box = document.getElementById(box_str).style.backgroundColor = rgba_val;
        return 4;
    }

    // Brown
    else if (year_diff >= 1){
        var opaque_value
        if (year_diff == 1){
            opaque_value = 1
        }
        else if (year_diff == 2){
            opaque_value = .85
        }
        else {
            opaque_value = .3 + (10-year_diff)/7 * .45
        }
        
        var rgba_val = "rgba(150,75,0,".concat(opaque_value.toString(), ")")
        var box = document.getElementById(box_str).style.backgroundColor = rgba_val;
        return 5;
    }

    // Green
    else if (year_diff == 0){
        var rgba_val = "rgba(0,156,52,1)"
        var box = document.getElementById(box_str).style.backgroundColor = rgba_val;
        return 6;
    }
    
    
}



function guess_button_onclick(year_input=-1){

    if (year_input == -1){
        var year_input = document.getElementById('year_input').value
        year_input = year_input.replace(/\D/g,'');
        if (year_input > 2025 || year_input == ''){
            alert("Please input a year in the past");
            document.getElementById('year_input').value = '';
            return false;
        }
        var guessArray_ = parseGuessArr()
        const year_input_ = Number(year_input);
        if (guessArray_.includes(year_input_)) {
            alert("Please guess a year you haven't guessed yet");
            document.getElementById('year_input').value = '';
            return False
        }
    }

    if (getCookie("played_today") != "True"){
        if (parseGuessArr().length < guess_number || getCookie("game_started") != "True"){
            if(guess_number == 1 || getCookie("game_started") != "True"){
                let bracket = "["
                let val = bracket.concat(year_input.toString(), "]")
                setCookie("guess_arr", val, 0, 2);
                setCookie("game_started", "True", 0, 2);
                // avg_score = guess_number
            }
            else{
                let arr_str = getCookie("guess_arr")
                let arr = arr_str.substring(1, arr_str.length-1)
                arr = arr.replace(/['"]+/g, '')
                score_vals = arr.split(',');
                score_vals.push(year_input.toString())
                if (getCookie("played_today") != "True"){
                    setCookie("guess_arr", JSON.stringify(score_vals), 0, 2)
                }
                
            }
        }
    }
    

    var year_diff1 = get_difference_time_guess(year_input, year1)
    var year_diff2 = get_difference_time_guess(year_input, year2)
    var year_diff3 = get_difference_time_guess(year_input, year3)
    


    var box1_str = "s".concat(guess_number.toString(), "_1");
    if (guess_number > 1 && output_grid[guess_number-2][0] == 6){
        output_grid[guess_number-1][0] = set_colors(box1_str, 0, year1)
    }
    else{
        output_grid[guess_number-1][0] = set_colors(box1_str, year_diff1, year1)
    }

    var box2_str = "s".concat(guess_number.toString(), "_2");
    if (guess_number > 1 && output_grid[guess_number-2][1] == 6){
        output_grid[guess_number-1][1] = set_colors(box2_str, 0, year2)
    }
    else{
        output_grid[guess_number-1][1] = set_colors(box2_str, year_diff2, year2)
    }

    var box3_str = "s".concat(guess_number.toString(), "_3");
    if (guess_number > 1 && output_grid[guess_number-2][2] == 6){
        output_grid[guess_number-1][2] = set_colors(box3_str, 0, year3);
    }
    else{
        output_grid[guess_number-1][2] = set_colors(box3_str, year_diff3, year3);
    }

    // fun animation
    if (output_grid[guess_number-1][0] == 6 && getCookie('animation_1') == "False"){
        animateSquare(box1_str, 10, 1000)
        setCookie("animation_1", "True", 0, 2)
    }
    if (output_grid[guess_number-1][1] == 6 && getCookie('animation_2') == "False"){
        animateSquare(box2_str, 10, 1000)
        setCookie("animation_2", "True", 0, 2)
    }
    if (output_grid[guess_number-1][2] == 6 && getCookie('animation_3') == "False"){
        animateSquare(box3_str, 10, 1000)
        setCookie("animation_3", "True", 0, 2)
    }


    var box0_str = "s".concat(guess_number.toString(), "_0");
    document.getElementById(box0_str).innerHTML = year_input


    // WIN CONDITION
    if (output_grid[guess_number-1][0] == 6 && output_grid[guess_number-1][1] == 6 && output_grid[guess_number-1][2] == 6){

        document.getElementsByClassName("stats")[0].style.visibility = 'visible';
        document.getElementById('end_response').textContent = 'YOU WIN!'
        document.getElementById('end_id').style.display = 'block'

        fire_fucking_works(guess_number)
        
        var utcDate = new Date(Date.UTC(
            clientDate.getFullYear(),
            clientDate.getMonth(),
            clientDate.getDate(),
            clientDate.getHours(),
            clientDate.getMinutes(),
            clientDate.getSeconds()
        ));
        utcDate = utcDate.toISOString();

        if (getCookie("played_today") != "True" && guess_number >= 3){
            if (guess_number != 1) {
                insert_row_php(guess_number, utcDate)
            }
        }
        add_histogram()
        
        var share_text = date.textContent + '\n' + guess_number.toString() + '/15\n\n'
        var display_share_text = ''
        var display_share_text_cookie = ''
        for (let i=0; i<guess_number; i++){
            for (let j=0; j<3; j++){
                // white square
                if (output_grid[i][j] == undefined || output_grid[i][j] == 0){
                    share_text += String.fromCodePoint(11036)
                    display_share_text += String.fromCodePoint(11036)
                    display_share_text_cookie += "W"
                } 

                // black square
                else if (output_grid[i][j] == 1){
                    share_text += String.fromCodePoint(11035)
                    display_share_text += String.fromCodePoint(11035)
                    display_share_text_cookie += "B"
                } 

                // blue square
                else if (output_grid[i][j] == 2){
                    share_text += String.fromCodePoint(128998)
                    display_share_text += String.fromCodePoint(128998)
                    display_share_text_cookie += "L"
                }

                //red square
                else if (output_grid[i][j] == 3){
                    share_text += String.fromCodePoint(128997)
                    display_share_text += String.fromCodePoint(128997)
                    display_share_text_cookie += "R"
                } 

                //orange square
                else if (output_grid[i][j] == 4){
                    share_text += String.fromCodePoint(128999)
                    display_share_text += String.fromCodePoint(128999)
                    display_share_text_cookie += "O"
                } 

                //brown square
                else if (output_grid[i][j] == 5){
                    share_text += String.fromCodePoint(129003)
                    display_share_text += String.fromCodePoint(129003)
                    display_share_text_cookie += "N"
                } 
                // green square                       
                else if (output_grid[i][j] == 6){
                    share_text += String.fromCodePoint(129001)
                    display_share_text += String.fromCodePoint(129001)
                    display_share_text_cookie += "G"
                }                        
                // share_text += output_grid[i][j]
            }
            share_text += '\n'
            display_share_text += '\n'
        }
        share_text += '\nhttps://today-in-history-game.com/share'
        share_text_ = share_text

        document.getElementById('share_text').textContent = display_share_text;
        
        let date_var = date.textContent
        document.getElementById('end_date').textContent = date_var.substring(18,date_var.length);
        document.getElementById('end_score').textContent = guess_number.toString() + '/15 \n'
        // var year2_ = document.createElement('year2_a')
        // year2_.textContent = year2
        // year2_.href = 'google.com'
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
        document.getElementById('year_input').disabled = true;
        document.getElementById("year_input").value = '';

        
        
        // document.cookie = "score_arr=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        if (getCookie("played_today") != "True"){
            if(getCookie("score_arr") == ""){
                let bracket = "["
                let val = bracket.concat(guess_number, "]")
                setCookie("score_arr", val, 0, 0);
                avg_score = guess_number
            }
            else{
                let arr_str = getCookie("score_arr")
                let arr = arr_str.substring(1, arr_str.length-1)
                arr = arr.replace(/['"]+/g, '')
                score_vals = arr.split(',');
                score_vals.push(guess_number.toString())
                setCookie("score_arr", JSON.stringify(score_vals), 0, 0)


                avg_score = parseAvgScore("score_arr")
                
            }

            if(getCookie("streak_arr") == ""){
                let bracket = "["
                let today = new Date();
                let year = today.getFullYear();
                let month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
                let day = String(today.getDate()).padStart(2, '0');

                let formattedDate = `${year}-${month}-${day}`;
                console.log(formattedDate);
                let val = bracket.concat(formattedDate, "]")
                setCookie("streak_arr", val, 0, 0);
                console.log('Streak Started ', val)

                setCookie("longest_streak", 1, 0, 0);

                console.log()
            }
            else{
                let cont_streak = parseStreakArr("streak_arr")

                if (cont_streak){
                    let arr_str = getCookie("streak_arr")
                    let arr = arr_str.substring(1, arr_str.length-1)
                    arr = arr.replace(/['"]+/g, '')
                    streak_vals = arr.split(',');
                    let today = new Date();
                    let year = today.getFullYear();
                    let month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
                    let day = String(today.getDate()).padStart(2, '0');

                    let today_ = `${year}-${month}-${day}`;

                    streak_vals.push(today_)
                    
                    setCookie("streak_arr", JSON.stringify(streak_vals), 0, 0)
                    console.log("Streak Continued ", streak_vals)

                    len_ = parseStreakLen("streak_arr")
                    setCookie("longest_streak", len_, 0, 0)
                }
                else{
                    let bracket = "["
                    let today = new Date();
                    let year = today.getFullYear();
                    let month = String(today.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
                    let day = String(today.getDate()).padStart(2, '0');

                    let today_ = `${year}-${month}-${day}`;

                    let val = bracket.concat(today_, "]")
                    setCookie("streak_arr", val, 0, 0);
                    console.log('Streak Restarted')
                }

                avg_score = parseAvgScore("score_arr")
                
                
            }

        }
        
        if (typeof avg_score == Number){
            document.getElementById('avg_score').textContent = 'Average Score = ' + avg_score.toFixed(2).toString()
        }
        else{
            document.getElementById('avg_score').textContent = 'Average Score = ' + avg_score.toString()
        }
        
        if (getCookie("played_today") == "True"){
            // pass
        }
        else{
            addNumGames()
            addWinGames()
        }
        

        let total_games_played = Number(getCookie("total_games"))
        document.getElementById('games_played').textContent = 'Games Played = ' + total_games_played
        
        var win_percentage = (Number(getCookie("win_games")) / Number(getCookie("total_games")) * 100).toFixed(1) 
        document.getElementById('win_percentage').textContent = 'Win Percentage = ' + win_percentage + '%'

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

        setCookie("display_share_text_cookie", display_share_text_cookie, 0, 2)
        setCookie("played_today", "True", 0, 2)
        setCookie("todays_score", guess_number.toString(), 0, 2)
        console.log("Today's Score", getCookie("todays_score"))

        return false
    }

    // LOSE CONDITION
    if (guess_number == 15){
        let date_var = date.textContent
        var share_text = date_var.substring(0,17) + "\n" + date_var.substring(19,date_var.length) + '\nX/15\n\n'
        var display_share_text = ''
        var display_share_text_cookie = ''

        document.getElementById('end_response').textContent = 'GAME OVER'
        document.getElementById('end_id').style.display = 'block'
        document.getElementsByClassName("stats")[0].style.visibility = 'visible';

        var utcDate = new Date(Date.UTC(
            clientDate.getFullYear(),
            clientDate.getMonth(),
            clientDate.getDate(),
            clientDate.getHours(),
            clientDate.getMinutes(),
            clientDate.getSeconds()
        ));
        utcDate = utcDate.toISOString();
        if (getCookie("played_today") != "True" && guess_number >= 3){
            insert_row_php(16, utcDate)
        }
        add_histogram()


        for (let i=0; i<guess_number; i++){
            for (let j=0; j<3; j++){
                // white square
                if (output_grid[i][j] == undefined || output_grid[i][j] == 0){
                    share_text += String.fromCodePoint(11036)
                    display_share_text += String.fromCodePoint(11036)
                    display_share_text_cookie += "W"
                } 

                // black square
                else if (output_grid[i][j] == 1){
                    share_text += String.fromCodePoint(11035)
                    display_share_text += String.fromCodePoint(11035)
                    display_share_text_cookie += "B"
                } 

                // blue square
                else if (output_grid[i][j] == 2){
                    share_text += String.fromCodePoint(128998)
                    display_share_text += String.fromCodePoint(128998)
                    display_share_text_cookie += "L"
                }

                //red square
                else if (output_grid[i][j] == 3){
                    share_text += String.fromCodePoint(128997)
                    display_share_text += String.fromCodePoint(128997)
                    display_share_text_cookie += "R"
                } 

                //orange square
                else if (output_grid[i][j] == 4){
                    share_text += String.fromCodePoint(128999)
                    display_share_text += String.fromCodePoint(128999)
                    display_share_text_cookie += "O"
                } 

                //brown square
                else if (output_grid[i][j] == 5){
                    share_text += String.fromCodePoint(129003)
                    display_share_text += String.fromCodePoint(129003)
                    display_share_text_cookie += "N"
                } 
                // green square                       
                else if (output_grid[i][j] == 6){
                    share_text += String.fromCodePoint(129001)
                    display_share_text += String.fromCodePoint(129001)
                    display_share_text_cookie += "G"
                }                        
            }
            share_text += '\n'
            display_share_text += '\n'
        }
        share_text += '\nhttps://today-in-history-game.com/share'
        share_text_ = share_text

        document.getElementById('share_text').textContent = display_share_text;
        // let date_var = date.textContent
        document.getElementById('end_date').textContent = date_var.substring(19,date_var.length);
        document.getElementById('end_score').textContent = 'X/15\n';
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
        document.getElementById('year_input').disabled = true;
        document.getElementById("year_input").value = '';

        


        if(getCookie("score_arr") != ""){
            let avg_score = parseAvgScore("score_arr")
            console.log("avg_score:", avg_score)
            if (typeof avg_score == Number){
                document.getElementById('avg_score').textContent = 'Average Score = ' + avg_score.toFixed(2).toString()
            }
            else{
                document.getElementById('avg_score').textContent = 'Average Score = ' + avg_score.toString()
            }
        }


        if (getCookie("played_today") == "True"){
            // pass
        }
        else{
            addNumGames()
        }

        let total_games_played = Number(getCookie("total_games"))
        document.getElementById('games_played').textContent = 'Games Played = ' + total_games_played
        document.getElementById("longest_streak").textContent = `Longest Streak = ${getCookie("longest_streak")}`

        if (getCookie("win_games") != ""){
            var win_percentage = (Number(getCookie("win_games")) / Number(getCookie("total_games")) * 100).toFixed(1) 
            document.getElementById('win_percentage').textContent = 'Win Percentage = ' + win_percentage + '%'
        }
        
        setCookie("display_share_text_cookie", display_share_text_cookie, 0, 2)
        setCookie("played_today", "True", 0, 2)
        setCookie("todays_score", "16", 0, 2)

        return false;
    }


    guess_number += 1;
    document.getElementById("year_input").value = '';


    

    return false
}

function close_end(){
    document.getElementById('end_id').style.visibility = 'hidden'
}

function copy_to_clipboard(){
    var share_text_post = ""
    if (getCookie("played_today") == "True"){
        
        if(getCookie("todays_score")=="16"){
            share_text_post = date.textContent + '\nX/15\n\n'
        }
        else{
            share_text_post = date.textContent + '\n' + getCookie("todays_score") + '/15\n\n'
        }
        
        var display_share_text_post = getCookie("display_share_text_cookie")
        display_share_text_post_post = post_play_display(display_share_text_post)

        share_text_post += display_share_text_post_post
        share_text_post += '\nhttps://today-in-history-game.com/share'

        var shareObject = {title:"Win", text:share_text_post}

    }
    else{
        var shareObject = {title:"Win", text:share_text_}
    }

    // Copy the text inside the text field
    navigator.clipboard.writeText(share_text_post);
}

function share_function(){
    // How to share with people. 
    if (getCookie("played_today") == "True"){
        
        if(getCookie("todays_score")=="16"){
            share_text_post = date.textContent + '\nX/15\n\n'
        }
        else{
            share_text_post = date.textContent + '\n' + getCookie("todays_score") + '/15\n\n'
        }
        
        var display_share_text_post = getCookie("display_share_text_cookie")
        display_share_text_post_post = post_play_display(display_share_text_post)

        share_text_post += display_share_text_post_post
        share_text_post += '\nhttps://today-in-history-game.com/share'

        var shareObject = {title:"Win", text:share_text_post}

    }
    else{
        var shareObject = {title:"Win", text:share_text_}
    }
    
    navigator.share(shareObject)
}

// function hardrefresh(){
//     console.log('hard refreshing')
//     function urlWithRndQueryParam(url, paramName) {
//         const ulrArr = url.split('#');
//         const urlQry = ulrArr[0].split('?');
//         const usp = new URLSearchParams(urlQry[1] || '');
//         usp.set(paramName || '_z', `${Date.now()}`);
//         urlQry[1] = usp.toString();
//         ulrArr[0] = urlQry.join('?');
//         return ulrArr.join('#');
//     }

//     function handleHardReload(url) {
//         window.location.href = urlWithRndQueryParam(url);
//         // This is to ensure reload with url's having '#'
//         window.location.reload();
//     }

//     handleHardReload(window.location.href);
// }
