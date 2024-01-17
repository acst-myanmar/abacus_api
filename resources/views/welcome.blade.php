<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Summernote</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
</head>

<body>
    <input type="number" id="line">
    <button onclick="setup(23)">speek</button>
    <button onclick="setupLittleFriend(10)">little friend</button>
    <button onclick="setupBigFriend(5)">big friend</button>

    <script>
        function setup(line) {
            generateQ(line)

            const interval = setInterval(function() {
                generateQ(line)
            }, (line + 5) * 1000);
        }

        function setupLittleFriend(line) {
            generateLittleFriendQ(line)
        }

        function setupBigFriend(line) {
            generateBigFriendQ(line)
        }

        function generateQ(line = 5) {
            // line = document.getElementById('line').value
            speak('Question start ready ')
            setTimeout(function() {
                console.log("Ready")
                let firstNum = (Math.floor(Math.random() * 10) + 1);
                while (firstNum == 10) {
                    firstNum = (Math.floor(Math.random() * 10) + 1);
                }
                let possibleOutcome = []
                let questions = [firstNum]

                for (let i = 0; i <= line; i++) {
                    switch (firstNum) {
                        case 0:
                            possibleOutcome = [1, 2, 3, 4, 5, 6, 7, 8, 9]
                            break;
                        case 1:
                            possibleOutcome = [1, 2, 3, 5, 6, 7, 8, -1]
                            break;
                        case 2:
                            possibleOutcome = [1, 2, 5, 6, 7, -1, -2]
                            break;
                        case 3:
                            possibleOutcome = [1, 5, 6, -1, -2, -3]
                            break;

                        case 4:
                            possibleOutcome = [5, -1, -2, -3, -4]
                            break;

                        case 5:
                            possibleOutcome = [1, 2, 3, 4, -5]
                            break;

                        case 6:
                            possibleOutcome = [1, 2, 3, -1, -5, -6]
                            break;
                        case 7:
                            possibleOutcome = [1, 2, -1, -2, -5, -6, -7]
                            break;
                        case 8:
                            possibleOutcome = [1, -1, -2, -3, -5, -6, -7, -8]
                            break;
                        case 9:
                            possibleOutcome = [-1, -2, -3, -4, -5, -6, -7, -8, -9]
                            break;
                    }
                    question = possibleOutcome[Math.floor(Math.random() * possibleOutcome.length)]
                    questions.push(question)
                    firstNum = questions.reduce((a, b) => a + b, 0)
                }

                questions.forEach((item, index) => {
                    if (index == 0) {
                        speak(item, 1)

                    } else {

                        speak(item, 1.5)
                    }
                })
                speak('the answer is ')
                let answer = questions.reduce((a, b) => a + b, 0)
                // setTimeout(() => {
                speak(answer)
                // }, 3000);
                console.log('questions', questions)
                console.log('answer', answer)
            }, 2000);
        }

        function directMethod(num) {
            let possibleOutcome = [];
            switch (num) {
                case 0:
                    possibleOutcome = [1, 2, 3, 4, 5, 6, 7, 8, 9]
                    break;
                case 1:
                    possibleOutcome = [1, 2, 3, 5, 6, 7, 8, -1]
                    break;
                case 2:
                    possibleOutcome = [1, 2, 5, 6, 7, -1, -2]
                    break;
                case 3:
                    possibleOutcome = [1, 5, 6, -1, -2, -3]
                    break;

                case 4:
                    possibleOutcome = [5, -1, -2, -3, -4]
                    break;

                case 5:
                    possibleOutcome = [1, 2, 3, 4, -5]
                    break;

                case 6:
                    possibleOutcome = [1, 2, 3, -1, -5, -6]
                    break;
                case 7:
                    possibleOutcome = [1, 2, -1, -2, -5, -6, -7]
                    break;
                case 8:
                    possibleOutcome = [1, -1, -2, -3, -5, -6, -7, -8]
                    break;
                case 9:
                    possibleOutcome = [-1, -2, -3, -4, -5, -6, -7, -8, -9]
                    break;
            }
            return possibleOutcome[Math.floor(Math.random() * possibleOutcome.length)]
        }

        function generateLittleFriendQ(numOfLine) {
            let firstNum = [1, 2, 3, 4, 5, 6, 7, 8, 9];

            firstNum = randNum(firstNum);
            let questions = [firstNum];
            for (let i = 1; i < numOfLine; i++) {
                firstNum = questions.reduce((a, b) => a + b, 0)
                if (firstNum == 9 || firstNum == 0) {
                    questions.push(directMethod(firstNum))
                } else {
                    questions.push(littleFriend(firstNum));
                }


            }
            questions.forEach((item, index) => {
                if (index == 0) {
                    speak(item, 1)

                } else {

                    speak(item, 1.1)
                }
            })
            speak('the answer is ')
            console.log('questions', questions)
            console.log(questions.reduce((a, b) => a + b, 0));

        }

        function generateBigFriendQ(numOfLine) {
            let firstNum = randomInt(1, 99);
            console.log('firstnum => ', firstNum)
            // firstNum = randNum(firstNum);
            let questions = [firstNum];
            for (let i = 1; i < numOfLine; i++) {
                questions.push(bigFriend(firstNum));
                firstNum = questions.reduce((a, b) => a + b, 0)
            }
            // questions.forEach((item, index) => {
            //     if (index == 0) {
            //         speak(item, 0.1)

            //     } else {

            //         speak(item, 0.1)
            //     }
            // })
            // speak('the answer is ')
            console.log('questions', questions)
            console.log(questions.reduce((a, b) => a + b, 0));

        }

        function littleFriend(num) {
            let result = 0;
            switch (num) {
                case 1:
                    result = 4;
                    break;
                case 2:
                    result = randNum([4, 3]);
                    break;
                case 3:
                    result = randNum([4, 3, 2]);
                    break;
                case 4:
                    result = randNum([4, 3, 2, 1]);
                    break;
                case 5:
                    result = randNum([-4, -3, -2, -1]);
                    break;
                case 6:
                    result = randNum([-4, -3, -2]);
                    break;
                case 7:
                    result = randNum([-4, -3]);
                    break;
                case 8:
                    result = -4;
                    break;
            }
            console.log('little friend =>', result)
            return result;
        }

        function getPlus(num) {
            let remainder = num % 10;

            let result = 0;
            switch (remainder) {
                case 1:
                    result = 9;
                    break;
                case 2:
                    result = randNum([9, 8]);
                    break;
                case 3:
                    result = randNum([9, 8, 7]);
                    break;
                case 4:
                    result = randNum([9, 8, 7, 6]);
                    break;
                case 5:
                    result = 5;
                    break;
                case 6:
                    result = randNum([9, 5, 4]);
                    break;
                case 7:
                    result = randNum([9, 8, 5, 4, 3]);
                    break;
                case 8:
                    result = randNum([9, 8, 7, 5, 4, 3, 2]);
                    break;
                case 9:
                    result = randNum([9, 8, 7, 6, 5, 4, 3, 2, 1]);
                    break;
                case 0:
                    result = randNum([9, 8, 7, 6, 5, 4, 3, 2, 1]);
                    break;


            }
            return result
        }

        function getMinus(num) {
            let remainder = num % 10;

            let result = 0;
            switch (remainder) {
                case 1:
                    result = randNum([-9, -8, -7, -5, -4, -3, -2]);
                    break;
                case 2:
                    result = randNum([-9, -8, -5, -4, -3]);
                    break;
                case 3:
                    result = randNum([-9, -5, -4]);
                    break;
                case 4:
                    result = -5;
                    break;
                case 5:
                    result = randNum([-9, -8, -7, -6]);
                    break;
                case 6:
                    result = randNum([-9, -8, -7]);
                    break;
                case 7:
                    result = randNum([-9, -8]);
                    break;
                case 8:
                    result = -9;
                    break;
                case 9:
                    result = -randomInt(1, 9);
                    break;
                case 0:
                    if (num >= 10) {
                        result = -randomInt(1, 9);
                    } else {
                        result = randomInt(1, 9);
                    }
                    break;

            }
            return result
        }

        function bigFriend(num) {

            let result = 0;
            if ((num >= 40 && num < 50) || (num >= 90 && num < 99)) {
                result = getMinus(num)
            } else {
                if (num % 10 == 9) { //[19,29,39,59,69,79,89]
                    result = getPlus(num)
                    console.log("only plus number=>",num)
                } else {
                    console.log('can plus or minus')
                    if (randNum(['+', '-']) == "+") {
                        console.log("+++++++++++")
                        result = getPlus(num)
                    } else {
                        console.log("-----------")
                        if(num>10){

                            result = getMinus(num)
                        }else{
                            result = getPlus(num)

                        }
                    }
                }

            }
            // need to add 0 condition ,consider add or sub base on num
            return result;
        }

        function randomInt(min, max) { // min and max included
            return Math.floor(Math.random() * (max - min + 1) + min)
        }

        function randNum(numbers) {
            return numbers[Math.floor(Math.random() * numbers.length)]
        }


        function speak(text, rate = 1, pitch = 1, volume = 1, voiceIndex = 0) {
            let synth = window.speechSynthesis;
            // const voices = synth.getVoices();
            const utterance = new SpeechSynthesisUtterance(text);
            // utterance.voice = voices[voiceIndex];
            utterance.rate = rate;
            utterance.pitch = pitch;
            utterance.volume = volume;
            synth.speak(utterance);

        }
    </script>
</body>

</html>
