<?php  
	/** 
	 * calc.php
	 * Author:raf
	 * @20150518
	 * This file provides the view and functionalities of the calculator.
	 */
	if( !isset($_SESSION['user'] ) ) {
		header("Location: /Calculator");
		die();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="ISO-8859-1">
		<title>Calculator</title>
		<style>
			body {
				font-family:"Segoe UI";
				background-color:#000;
				color:white;
			}
		
			header {
				text-align:right;
				padding:0 10px 10px;
				position:relative;
				height:50px;
			}
			
			table {
				background-color:#330000;
				margin:auto;
				padding:10px;
			}
			
			#screen, #words {
				border:#FFF solid 2px;
				min-width:300px;
				font-size:2em;
				text-align:right;
				padding:10px;
				background-color:red;
			}
			
			#words {
				font-size:1em;
				margin:10px 0;
				width:300px;
				text-align:justify;
				word-break:break-all;
			}
			
			a, button {
				width:75px;
				border:none;
				text-decoration:none;
				background-color:red;
				border-radius:100%;
				padding:10px;
				color:white;
				font-size:1.5em;
			}
			
			a {
				display:block;
				text-align:center;
				font-weight:bold;
				position:absolute;
				top:5px;
				right:20px;
				font-size:1em;
			}
			
			[id="clear"] {
				width:150px;
			}
			
			td {
				padding:10px;
				text-align:center;
			}
			
			th {
				color:white;
				font-size:2em;
				padding:10px 5px 5px;
			}
			
			#mainContent {
				background-color:#330000;
				border-radius:50px;
				width:450px;
				padding:30px;
				margin:auto;
			}
		</style>
		<script src="jquery-2.1.1.js"></script>
		<script>
			var input = '0'; //current input
			var result = null; //result of operation
			var oper = null; //operation in effect
			var isInput = true; //whether display is input or not
			var suffixes = [ //suffixes for words
				'',
				'thousand, and ',
				'million, ',
				'billion, ',
				'trillion, ',
				'quadrillion, ',
				'quintillion, ',
				'sextillion, ',
				'septillion, ',
				'octillion, ',
				'nonillion, '
			];
			
			/**
			* sets the value of the word field on the calculator with the 
			* result of the operation in words
			* Parameters:
			* 	num - number to convert
			*/
			function convertToString(num) {
				var string = ""; //output
				var level = 0; //current log base 1000
				var negative = false; //if negative
				
				//if not integral
				if( num % 1 !== 0 ) {
					$("#words").hide();
					
				//if zero
				} else if( num == 0 ) {
					$("#words").show().text("zero").css("width"
							,$("#screen").css("width") );
				
				//if nonzero integer
				} else {
					//if negative
					if( num < 0 ) {
						negative = true;
						num *= -1;
					}
					
					//while number is not yet consumed
					while( num > 0 ) {
						//get triplet
						var threeDigits = num % 1000;
						var temp = ""; //triplet in words
						
						//if triplet is not zero
						if( threeDigits != 0 ) {
							//convert hundreds digit
							switch(  Math.floor(threeDigits / 100 ) ) {
								case 1:
									temp += "one hundred ";
									break;
								case 2:
									temp += "two hundred ";
									break;
								case 3:
									temp += "three hundred ";
									break;
								case 4:
									temp += "four hundred ";
									break;
								case 5:
									temp += "five hundred ";
									break;
								case 6:
									temp += "six hundred ";
									break;
								case 7:
									temp += "seven hundred ";
									break;
								case 8:
									temp += "eight hundred ";
									break;
								case 9:
									temp += "nine hundred ";
									break;
								default:	
							}
							
							//convert tens digit
							switch( Math.floor(threeDigits % 100 / 10 ) ) {
								case 2:
									temp += "twenty-";
									break;
								case 3:
									temp += "thirty-";
									break;
								case 4:
									temp += "forty-";
									break;
								case 5:
									temp += "fifty-";
									break;
								case 6:
									temp += "sixty-";
									break;
								case 7:
									temp += "seventy-";
									break;
								case 8:
									temp += "eighty-";
									break;
								case 9:
									temp += "ninety-";
									break;
								default:	
							}
	
							//if triplet mod 100 outside interval [10,19]
							if( Math.floor(threeDigits % 100 / 10 ) != 1 ) {
								switch( threeDigits % 10 ) {
									case 1:
										temp += "one ";
										break;
									case 2:
										temp += "two ";
										break;
									case 3:
										temp += "three ";
										break;
									case 4:
										temp += "four ";
										break;
									case 5:
										temp += "five ";
										break;
									case 6:
										temp += "six ";
										break;
									case 7:
										temp += "seven ";
										break;
									case 8:
										temp += "eight ";
										break;
									case 9:
										temp += "nine ";
										break;
									default:	
								}
								
							//if within interval [10,19]
							} else {
								switch( threeDigits % 10 ) {
									case 0:
										temp += "ten ";
										break;
									case 1:
										temp += "eleven ";
										break;
									case 2:
										temp += "twelve ";
										break;
									case 3:
										temp += "thirteen ";
										break;
									case 4:
										temp += "fourteen ";
										break;
									case 5:
										temp += "fifteen ";
										break;
									case 6:
										temp += "sixteen ";
										break;
									case 7:
										temp += "seventeen ";
										break;
									case 8:
										temp += "eighteen ";
										break;
									case 9:
										temp += "nineteen ";
										break;
									default:	
								}
							}
							
							//if hanging tens translation
							if( temp.charAt(temp.length - 1) == '-' ) {
								temp = temp.substring( 0, temp.length - 1 ) 
										+ ' ';
							}
							
							//affix suffix
							temp += suffixes[level];
							
							//add to output
							string = temp + string;
						}
						
						level++; //increment level
						num = Math.floor(num/1000); //trim number
					}

					//if hanging comma
					if( string.charAt(string.length - 2) == ',' ) {
						
						//trim
						string = string.substring(0,string.length - 2 );
						
					//if hanging end
					} else if( string.substring( string.length - 4
								, string.length - 1 ) == 'and' ) {
						
						//trim
						string = string.substring(0, string.length - 6 );
					}
					
					if( negative ) {
						string = "negative " + string;
					}
					
					//display words
					$("#words").show().text(string).css("width"
							,$("#screen").css("width") );
				}
			}
			
			/***
			* processes an operation
			*/
			function processOper() {
				//perform appropriate operation
				if( input == null ) {
					if( oper == 'x' || oper == '/' ) {
						input = '1';
					} else if( oper == '+' || oper == '-' ) {
						input = '0';
					} 
				}
				
				switch( oper ) {
					case '+': 
						result = result * 1 + input * 1;
						break;
					case '-': 
						result = result * 1 - input * 1;
						break;
					case 'x': 
						result = result * 1 * input * 1;
						break;
					case '/': 
						result = result * 1 / input * 1;
						break;
					default: //if no operation yet
						result = input;
				}
				$("#screen").text(result);
				convertToString(result);
				isInput = false;
				input = null;
			}
			
			/**
			* negates input in display and local variables
			*/
			function negateInput() {
				if( input.substring(0,1) == '-' ) {
					input = input.substring(1);
				} else {
					input = "-" + input;
				}
				$("#screen").text(input);
			}
			
			$(document).ready(function() {
				$("#words").hide();
				
				/**
				* manages digit press
				*/
				$("[id^='num']").click(function() {
					var val = $(this).attr("id").substring(3); //get digit
					
					//if input is initial value
					if( input == '0' || input == null ) {
						input = val;
					} else {
						input += val;
					}
					
					//set screen
					$("#screen").text(input);
					convertToString(input);
					isInput = true;
				});
				
				/**
				* manages addition
				*/
				$("#plus").click(function() {
					processOper();
					oper = '+'; //set operation
				});
				
				/**
				* manages subtraction
				*/
				$("#minus").click(function() {
					processOper();
					oper = '-';//set operation
				});
				
				/**
				* manages multiplication
				*/
				$("#times").click(function() {
					processOper();
					oper = 'x';//set operation
				});
				
				/**
				* manages division
				*/
				$("#divide").click(function() {
					processOper();
					oper = '/';//set operation
				});
				
				/**
				* manages evaluation
				*/
				$("#equals").click(function() {
					processOper();
					input = null; 
					oper = '+'; //set dummy operation
				});
				
				/**
				* manages clearing of input
				*/
				$("#clear").click(function() {
					//resets values
					input = '0';
					result = null;
					oper = null;
					$("#screen").text("0");
					$("#words").hide();
				});
				
				/**
				* manages clearing of current entry
				*/
				$("#clearEntry").click(function() {
					if( isInput ) { //if display is showing an input
						if( input != '0' ) { //if there is valid input
							//if input is longer than 1 character
							if( input.length > 1 ) {
								//trim last char
								input = input.substring(0,input.length - 1);
								
								//if ends with decimal point
								if( input.charAt( input.length - 1) == '.' ) {
									//remove decimal point
									input = input.substring( 0
												, input.length - 1 );
								}
								
								//if only sign left
								if( input == '-' ) {
									input = '0';
								}
							} else {
								//set to zero
								input = '0';	
							}
						};
						$("#screen").text(input);
						convertToString(input);
					}
				});
				
				/**
				* handles appending of decimal point
				*/
				$("#deci").click(function() {
					var hasDec = false;
					
					//searches input for decimal point
					for( var i = 0; !hasDec && i < input.length; i++ ) {
						if( input.charAt(i) == '.' ) {
							hasDec = true;
						}
					}
					
					//if no decimal point yet
					if( !hasDec ) {
						input += '.';
						$("#screen").text(input);
					}
				});
				
				/**
				* manages sign
				*/
				$("#sign").click(function() {
					if( isInput && input !== null && input != '0' ) {
						negateInput();
						convertToString(input);
					} else if( !isInput && result != null ) {
						input = String(result);
						negateInput();
						convertToString(input);
						result = input;
						input = null;
						oper = '+'; //set dummy operation
					}
				});
				
				/**
				* prevents border from appearing on buttons
				*/
				$("button").mousedown(function(e) {
					e.preventDefault();
				});
			});
		</script>
	</head>
	<body>
		<header>
			<a href="logout.php">Logout</a>
		</header>
		<div id="mainContent">
			<table>
				<tr>
					<th colspan="4">Calculator</th>
				</tr>
				<tr>
					<td colspan="4">
						<div id="screen">0</div>
						<div id="words"></div>
					</td>
				</tr>
				<tr>
					<td><button id="num1">1</button></td>
					<td><button id="num2">2</button></td>
					<td><button id="num3">3</button></td>
					<td><button id="plus">+</button></td>
				</tr>
				<tr>
					<td><button id="num4">4</button></td>
					<td><button id="num5">5</button></td>
					<td><button id="num6">6</button></td>
					<td><button id="minus">-</button></td>
				</tr>
				<tr>
					<td><button id="num7">7</button></td>
					<td><button id="num8">8</button></td>
					<td><button id="num9">9</button></td>
					<td><button id="times">x</button></td>
				</tr>
				<tr>
					<td><button id="num0">0</button></td>
					<td><button id="deci">.</button></td>
					<td><button id="equals">=</button></td>
					<td><button id="divide">/</button></td>
				</tr>
				<tr>
					<td><button id="sign">+/-</button></td>
					<td><button id="clearEntry">CE</button></td>
					<td colspan="2"><button id="clear">C</button></td>
				</tr>	
			</table>
		</div>
	</body>
</html>