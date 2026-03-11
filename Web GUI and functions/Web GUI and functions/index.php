<?php
session_start();
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}
?>
<?php
$conn = new mysqli("localhost", "root", "", "cash_for_trash");
?>

<!DOCTYPE html>
<html>
<head>
<title>Cash for Trash Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI', sans-serif;
}

body{
    background: linear-gradient(135deg,#0f3d2e,#14532d,#064e3b);
    min-height:100vh;
    padding:30px;
    color:white;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.header h1{
    font-size:28px;
    background: linear-gradient(90deg,#22c55e,#facc15);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.logout-btn{
    background:rgba(255,255,255,0.15);
    padding:10px 18px;
    border-radius:25px;
    text-decoration:none;
    color:white;
    font-size:14px;
    transition:0.3s;
    border:1px solid rgba(255,255,255,0.2);
}

.logout-btn:hover{
    background:#facc15;
    color:#064e3b;
}

/* STAT CARDS */
.stats{
    display:flex;
    justify-content:center;
    gap:40px;
    margin-bottom:30px;
    flex-wrap:wrap;
}

.stat-card{
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(20px);
    padding:30px;
    border-radius:20px;
    width:260px;
    text-align:center;
    box-shadow:0 20px 40px rgba(0,0,0,0.4);
    border:1px solid rgba(255,255,255,0.2);
    transition:0.3s;
}

.stat-card:hover{
    transform:translateY(-5px);
}

.stat-card h3{
    font-size:16px;
    opacity:0.8;
}

.stat-card p{
    font-size:36px;
    margin-top:10px;
    font-weight:bold;
    color:#facc15;
}

/* WEEK NAVIGATION */
.week-nav{
    text-align:center;
    margin-bottom:20px;
}

.week-nav button{
    padding:10px 18px;
    border:none;
    border-radius:25px;
    background:#22c55e;
    color:white;
    font-weight:bold;
    cursor:pointer;
    margin:0 10px;
    transition:0.3s;
}

.week-nav button:hover{
    background:#facc15;
    color:#064e3b;
}

#weekLabel{
    font-weight:bold;
}

/* DATE BOXES */
#dateSelector{
    display:flex;
    justify-content:center;
    gap:15px;
    flex-wrap:wrap;
    margin-bottom:25px;
}

#dateSelector div{
    background:rgba(255,255,255,0.12);
    backdrop-filter:blur(15px);
    padding:15px;
    width:140px;
    border-radius:15px;
    text-align:center;
    cursor:pointer;
    border:1px solid rgba(255,255,255,0.2);
    transition:0.3s;
}

#dateSelector div:hover{
    background:#facc15;
    color:#064e3b;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
    background:rgba(255,255,255,0.1);
    backdrop-filter:blur(20px);
    border-radius:15px;
    overflow:hidden;
}

th, td{
    padding:14px;
    text-align:center;
}

th{
    background:#22c55e;
    color:white;
    font-weight:bold;
}

tr:nth-child(even){
    background:rgba(255,255,255,0.05);
}

tr:hover{
    background:rgba(250,204,21,0.3);
    color:#064e3b;
}
.dashboard-btn{
    padding:12px 22px;
    border:none;
    border-radius:25px;
    background:#22c55e;
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
    box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

.dashboard-btn:hover{
    background:#facc15;
    color:#064e3b;
    transform:translateY(-3px);
}
#refillInput{
    padding:10px 15px;
    border-radius:20px;
    border:none;
    outline:none;
    margin-right:10px;
    width:180px;
}
</style>
</head>

<body>
<div class="header">
    <h1>♻ Trash for Cash Monitoring System</h1>
    <div>
        <a href="homepage.html" class="logout-btn" style="margin-right:10px;">🏠 Homepage</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </div>
</div>

<div style="text-align:center; margin-bottom:20px;">
    <button class="dashboard-btn" onclick="backToLive()">
        🔴 Live Logs
    </button>
</div>

<div class="stats">
    <div class="stat-card">
        <h3>Total Trash This Week</h3>
        <p id="weeklyTrash">0</p>
    </div>

    <div class="stat-card">
        <h3>Total Coins This Week</h3>
        <p id="weeklyCoins">0</p>
    </div>
</div>
<div id="coinAnimation" style="
    display:none;
    position:fixed;
    top:50%;
    left:50%;
    transform:translate(-50%, -50%);
    font-size:80px;
">
    🪙
</div>

<div style="display:flex; justify-content:center; gap:40px; margin:20px;">

    <div class="stat-card">
        <h3>Coins Remaining</h3>
        <p id="coinsRemaining">0</p>
    </div>

</div>

<div style="margin:20px; text-align:center;">
    <h3>Refill Coin Storage</h3>

    <input type="number" id="refillInput" min="0" placeholder="Enter coin amount">
    <button class="dashboard-btn" onclick="refillCoins()">Update Storage</button>
</div>
 
<div class="week-nav">
    <button onclick="changeWeek(-7)">⬅ Previous</button>
    <span id="weekLabel"></span>
    <button onclick="changeWeek(7)">Next ➡</button>
</div>

<div style="text-align:center; margin:20px;">
    <button class="dashboard-btn" onclick="exportWeek()">📄 Export Weekly Report</button>
</div>

<div id="dateSelector"></div>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Coins Given</th>
    <th>Date and Time</th>
</tr>
</thead>
<tbody id="logTable">
</tbody>
</table>

<script>
// ✅ ALL YOUR ORIGINAL JS IS 100% UNTOUCHED
let currentView = "all"; 
let logInterval = null;

function startLiveRefresh(){
    if(logInterval !== null) return; // already running
    logInterval = setInterval(fetchLogs, 2000);
}

function stopLiveRefresh(){
    if(logInterval !== null){
        clearInterval(logInterval);
        logInterval = null;
    }
}
function fetchLogs() {
    fetch("fetch_logs.php")
        .then(response => response.json())
        .then(data => {
            let table = document.getElementById("logTable");
            table.innerHTML = "";

            data.forEach(row => {
                let newRow = `
                    <tr>
                        <td>${row.id}</td>
                        <td>${row.coin_dispensed == 1 ? "Yes" : "No"}</td>
                        <td>${row.created_at}</td>
                    </tr>
                `;
                table.innerHTML += newRow;
            });
        let today = new Date().toISOString().split('T')[0];

        let todayCount = data.filter(row => 
            row.created_at.startsWith(today)
        ).length;

        let remainder = todayCount % 9;

        document.getElementById("coinProgress").innerText =
            remainder + " / 9";
        });
}

function loadDailySummary(){
    fetch("daily_summary.php")
        .then(res => res.json())
        .then(data => {
            let container = document.getElementById("dateSelector");
            container.innerHTML = "";

            data.forEach(day => {
                let box = document.createElement("div");

                box.innerHTML = `
                    <strong>${day.day}</strong><br>
                    Trash: ${day.total_trash}<br>
                    Coins: ${day.total_coins ?? 0}
                `;

                box.onclick = function(){
                    loadDayDetails(day.day);
                };

                container.appendChild(box);
            });
        });
}

function loadDayDetails(date){

    currentView = "day";

    stopLiveRefresh();

    fetch("day_details.php?date=" + date)
        .then(res => res.json())
        .then(data => {

            let table = document.getElementById("logTable");
            table.innerHTML = "";

            if(data.length === 0){
                table.innerHTML = `
                    <tr>
                        <td colspan="3">No trash collected on this day</td>
                    </tr>
                `;
                return;
            }

            data.forEach(row => {

                let newRow = `
                    <tr>
                        <td>${row.id}</td>
                        <td>${row.coin_dispensed == 1 ? "Yes" : "No"}</td>
                        <td>${row.created_at}</td>
                    </tr>
                `;

                table.innerHTML += newRow;
            });

        });
}

let currentWeekStart = getSunday(new Date());

function getSunday(date){
    let d = new Date(date);
    let day = d.getDay();
    let diff = d.getDate() - day;
    return new Date(d.setDate(diff));
}

function formatDate(date){
    return date.toISOString().split('T')[0];
}

function updateWeekLabel(){
    let start = new Date(currentWeekStart);
    let end = new Date(start);
    end.setDate(start.getDate() + 6);

    document.getElementById("weekLabel").innerText =
        start.toDateString() + " - " + end.toDateString();
}

function changeWeek(days){
    currentWeekStart.setDate(currentWeekStart.getDate() + days);
    loadWeek();
}

function loadWeek(){
    updateWeekLabel();

    fetch("weekly_summary_data.php?start=" + formatDate(currentWeekStart))
        .then(res => res.json())
        .then(data => {
            let weeklyTrash = 0;
            let weeklyCoins = 0;

            data.forEach(day => {
                weeklyTrash += Number(day.total_trash);
                weeklyCoins += Number(day.total_coins || 0);
            });

            document.getElementById("weeklyTrash").innerText = weeklyTrash;
            document.getElementById("weeklyCoins").innerText = weeklyCoins;
            
            let container = document.getElementById("dateSelector");
            container.innerHTML = "";

            let weekDays = [];

            for(let i = 0; i < 7; i++){
                let d = new Date(currentWeekStart);
                d.setDate(d.getDate() + i);

                weekDays.push(formatDate(d));
            }

            weekDays.forEach(dayDate => {

                let found = data.find(d => d.day === dayDate);

                let trash = found ? found.total_trash : 0;
                let coins = found ? found.total_coins : 0;

                let box = document.createElement("div");

                let d = new Date(dayDate);

                box.innerHTML = `
                    <strong>${d.toLocaleDateString('en-US', { weekday:'short' })}</strong><br>
                    ${dayDate}<br>
                    Trash: ${trash}<br>
                    Coins: ${coins}
                `;

                box.onclick = function(){
                    loadDayDetails(dayDate);
                };

                container.appendChild(box);
            });
        });
}
function loadMachineStatus(){
    fetch("fetch_machine_status.php")
        .then(res => res.json())
        .then(data => {
            document.getElementById("coinsRemaining").innerText =
                data.coins_remaining;

            if(data.coins_remaining <= 2){
                document.getElementById("coinsRemaining").style.color = "red";
            }
        });
}
function showCoinAnimation(){
    let coin = document.getElementById("coinAnimation");
    coin.style.display = "block";
    coin.style.animation = "pop 1s ease";

    setTimeout(() => {
        coin.style.display = "none";
    }, 1000);
}
function refillCoins(){
    let amount = document.getElementById("refillInput").value;

    fetch("update_coins.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "coins=" + amount
    })
    .then(res => res.text())
    .then(() => {
        loadMachineStatus();
        alert("Coin storage updated successfully.");
    });
}
function exportWeek(){
    window.location.href =
        "weekly_summary.php?start=" + formatDate(currentWeekStart);
}
function backToLive(){

    currentView = "all";

    fetchLogs();

    startLiveRefresh();
}
function resetCoinProgress(){
    const COIN_THRESHOLD = 9; // keep it consistent with your new threshold
    document.getElementById("coinProgress").innerText = `0 / ${COIN_THRESHOLD}`;

    // OPTIONAL: if you want to reset the Arduino/daily counter in the backend
    /*
    fetch("reset_daily_counter.php", {
        method: "POST"
    }).then(res => res.text())
      .then(() => console.log("Daily counter reset in DB"));
    */
}

showCoinAnimation();
loadMachineStatus();
setInterval(loadMachineStatus, 3000);
loadWeek();
fetchLogs();
startLiveRefresh();
</script>

</body>
</html>