<?php
//SOURCE: http://www.phpclasses.org/package/6290-PHP-Check-whether-a-password-is-strong.html
//Exapmle of use
//$rrr = new classPasswordMeter('ASSa12@sr');
//$aaa = $rrr->CalculateStrenght();
class classPasswordMeter{

public $password;
public $brojpokusaja = 60000000; //Bruteforce attack - attacks in minute

public function __construct($password) {
    $this->password = $password;
	$this->passLenght = strlen($this->password);

}

private function firstLetter_pairs_Entropy(){
	
	$podeljenoNAslova = str_split($this->password);
	$entropy = 0;

for($i = 0; $i < count($podeljenoNAslova)-1; $i++){
	preg_match('/[A-Z]/', $podeljenoNAslova[$i], $matchLettersUpper);
	preg_match('/[a-z]/', $podeljenoNAslova[$i], $matchLettersLower);
	preg_match('/[0-9]/', $podeljenoNAslova[$i], $matchNumbers);
	preg_match('/[^A-Za-z0-9]/', $podeljenoNAslova[$i], $matchSpec);
	preg_match('/[A-Z]/', $podeljenoNAslova[$i+1], $matchLettersUpper1);
	preg_match('/[a-z]/', $podeljenoNAslova[$i+1], $matchLettersLower1);
	preg_match('/[0-9]/', $podeljenoNAslova[$i+1], $matchNumbers1);
	preg_match('/[^A-Za-z0-9]/', $podeljenoNAslova[$i+1], $matchSpec1);
		
	if(!empty($matchLettersUpper[0]) && !empty($matchLettersUpper1[0])){
		if($matchLettersUpper[0] !== $matchLettersUpper1[0]){
		$entropy += 4.7;
		}else{
		$entropy += 0;
		}
	}elseif(!empty($matchLettersUpper[0]) && !empty($matchLettersLower1[0])){
		$UpperTOlower = strtolower($matchLettersUpper[0]);
		
		if($matchLettersLower1[0] !== $UpperTOlower){
		$entropy += 5.7;
		}else{
		$entropy += 2.35;
		}
	}elseif(!empty($matchLettersUpper[0]) && !empty($matchNumbers1[0])){
		$entropy += 5.17;
	}elseif(!empty($matchLettersUpper[0]) && !empty($matchSpec1[0])){
		$entropy = 5.86;
	}elseif(!empty($matchLettersLower[0]) && !empty($matchLettersLower1[0])){
		if($matchLettersLower[0] !== $matchLettersLower1[0]){
		$entropy += 5.7;
		}else{
		$entropy += 0;
		}
}elseif(!empty($matchLettersLower[0]) && !empty($matchLettersUpper1[0])){
		$UpperTOlower = strtolower($matchLettersUpper1[0]);
		
		if($matchLettersLower[0] !== $UpperTOlower){
		$entropy += 5.7;
		}else{
		$entropy += 2.35;
		}
}elseif(!empty($matchLettersLower[0]) && !empty($matchNumbers1[0])){
		$entropy += 5.17;
}elseif(!empty($matchLettersLower[0]) && !empty($matchSpec1[0])){
		$entropy += 5.86;
}elseif(!empty($matchNumbers[0]) && !empty($matchNumbers1[0])){
		if($matchNumbers[0] !== $matchNumbers1[0]){
		$entropy += 3.32;
		}else{
		$entropy += 0;
		}
}elseif(!empty($matchNumbers[0]) && !empty($matchSpec1[0])){
		$entropy += 	5.39;
}elseif(!empty($matchNumbers[0]) && !empty($matchLettersUpper1[0]) || !empty($matchNumbers[0]) && !empty($matchLettersLower1[0]) ){
		$entropy += 	5.17;
}elseif(!empty($matchSpec[0]) && !empty($matchSpec1[0])){
		if($matchSpec[0] !== $matchSpec1[0]){
		$entropy += 5;
		}else{
		$entropy += 0;
		}
}elseif(!empty($matchSpec[0]) && !empty($matchNumbers1[0])){
		$entropy += 	5.39;
}elseif((!empty($matchSpec[0])  && !empty($matchLettersUpper1[0]))  || (!empty($matchSpec[0])  && !empty($matchLettersLower1[0]) ) ){
		$entropy += 	5.36;
}

}
	return  round($entropy, 2);
	
}

private function ChrRepeat(){
$repeatLetter = 0;
	$charRepeat = count_chars($this->password ,1);
	$chIDandCount = array();
foreach($charRepeat as $key=>$val){
	$chIDandCount["".chr($key).""] = $val;
		if($val > 1){
		$repeatLetter += ($val-1);
		}
}
		
return array('noRepeatLettersArray'=>$chIDandCount, 'RepeatLettersPoints'=>$repeatLetter);
}

private function ChrExist(){

	$chIDandCount= $this->ChrRepeat();
	
	$NoRepeatChar = implode("", array_keys($chIDandCount['noRepeatLettersArray']));
//*******************************************************************
	preg_match_all('/[A-Z]/', $NoRepeatChar, $UpperL);
	preg_match_all('/[a-z]/', $NoRepeatChar, $LowerL);
	preg_match_all('/[0-9]/', $NoRepeatChar, $numbersL);
	preg_match_all('/[^a-zA-Z0-9]/', $NoRepeatChar, $specL);
	
	$strengthUpper = count($UpperL[0]);
	$strengthLower = count($LowerL[0]);
	$strengthNumbers = count($numbersL[0]);
	$strengthSpec = count($specL[0]);
//*******************************************************************
if($strengthUpper > 0 && $strengthLower > 0 && $strengthNumbers > 0 && $strengthSpec> 0 ){
	$singleUpper = $singleLower = $singleNumbers = $singleSpec = 6.55;
	$chNum = 94;
}elseif($strengthUpper > 0 && $strengthLower > 0 && $strengthNumbers > 0 && $strengthSpec == 0 ){
	$singleUpper = $singleLower = $singleNumbers =  5.95;
	$chNum = 62;
}elseif($strengthUpper == 0 && $strengthLower > 0 && $strengthNumbers > 0 && $strengthSpec > 0 ){
	$singleLower = $singleNumbers = $singleSpec = 6.08;
	$chNum = 68;
}elseif($strengthUpper > 0  && $strengthNumbers > 0 && $strengthSpec > 0 && $strengthLower == 0){
	$singleUpper = $singleNumbers = $singleSpec = 6.08;
	$chNum = 68;
}elseif($strengthUpper > 0 && $strengthLower > 0 && $strengthNumbers == 0 && $strengthSpec > 0 ){
	$singleUpper = $singleLower = $singleSpec = 6.4;
	$chNum = 84;
}elseif($strengthUpper > 0 && $strengthLower > 0 && $strengthNumbers == 0 && $strengthSpec == 0 ){
	$singleUpper = $singleLower  = 5.7;
	$chNum = 52;
}elseif($strengthUpper > 0 && $strengthLower == 0 && $strengthNumbers > 0 && $strengthSpec == 0 ){
	$singleUpper = $singleNumbers  = 5.17;
	$chNum = 36;
}elseif($strengthUpper > 0 && $strengthLower == 0 && $strengthNumbers == 0 && $strengthSpec > 0 ){
	$singleUpper = $singleSpec  = 5.86;
	$chNum = 58;
}elseif($strengthUpper == 0 && $strengthLower > 0 && $strengthNumbers > 0 && $strengthSpec == 0 ){
	$singleLower = $singleNumbers  = 5.17;
	$chNum = 36;
}elseif($strengthUpper == 0 && $strengthLower > 0 && $strengthNumbers == 0 && $strengthSpec > 0 ){
	$singleLower = $singleSpec  = 5.86;
	$chNum = 58;
}elseif($strengthUpper == 0 && $strengthLower == 0 && $strengthNumbers > 0 && $strengthSpec > 0 ){
	$singleNumbers = $singleSpec  = 5.39;
	$chNum = 42;
}elseif($strengthUpper == 0 && $strengthLower > 0 && $strengthNumbers == 0 && $strengthSpec == 0 ){
	$singleLower  = 4.7;
	$chNum = 26;
}elseif($strengthUpper > 0 && $strengthLower == 0 && $strengthNumbers == 0 && $strengthSpec == 0 ){
	$singleUpper  = 4.7;
	$chNum = 26;
}elseif($strengthUpper == 0 && $strengthLower == 0 && $strengthNumbers > 0 && $strengthSpec == 0 ){
	$singleNumbers  = 3.22;
	$chNum = 10;
}elseif($strengthUpper == 0 && $strengthLower == 0 && $strengthNumbers == 0 && $strengthSpec > 0 ){
	$singleSpec  = 5;
	$chNum = 32;
}
//*******************************************************************
if($strengthUpper == 0){
	$passScoreUpper = 0 ;
	$passpointsUpper = -50 ;
}elseif($strengthUpper == 1 && !array_sum($chIDandCount['noRepeatLettersArray']) == $this->passLenght ){
	$passpointsUpper = $passScoreUpper = $singleUpper ;
}else{
	$passpointsUpper = $passScoreUpper = $singleUpper  * $strengthUpper;
}
//****************
if($strengthLower == 0){
	$passScoreLower = 0;
	$passpointsLower = -50;
}elseif($strengthLower == 1 && !array_sum($chIDandCount['noRepeatLettersArray']) == $this->passLenght ){
	$passpointsLower = $passScoreLower = $singleLower;
}else{
	$passpointsLower = $passScoreLower = $singleLower * $strengthLower ;
}
//****************
if($strengthNumbers == 0){
	$passScoreNumbers = 0;
	$passpointsNumbers = -50;
}elseif($strengthNumbers == 1 && !array_sum($chIDandCount['noRepeatLettersArray']) == $this->passLenght ){
	$passpointsNumbers = $passScoreNumbers = $singleNumbers;
}else{
	$passpointsNumbers = $passScoreNumbers = $singleNumbers * $strengthNumbers ;
}
//****************
if($strengthSpec == 0){
	$passScoreSpec = 0 ;
	$passpointsSpec = -50;
}elseif($strengthSpec == 1 && !array_sum($chIDandCount['noRepeatLettersArray']) == $this->passLenght ){
	$passpointsSpec = $passScoreSpec = $singleSpec;
}else{
	$passpointsSpec = $passScoreSpec = $singleSpec * $strengthSpec ;
}
//==============================================================================================
	$ukupnoPoena = $passScoreUpper + $passScoreLower + $passScoreNumbers + $passScoreSpec;
	$ukupnoPoena1 = $passpointsUpper + $passpointsLower + $passpointsNumbers + $passpointsSpec;
	
	return 	array("usedChr"=>$chNum, "poitsSum"=>$ukupnoPoena1, "entropySum"=>$ukupnoPoena);
}	

private function passLenght(){

	if($this->passLenght < 5){
	$passScore = 5;
	}elseif($this->passLenght < 5){
	$passScore = 10;
	}elseif($this->passLenght < 7){
	$passScore = 15;
	}elseif($this->passLenght < 9){
	$passScore = 20;
	}elseif($this->passLenght < 11){
	$passScore = 25;
	}elseif($this->passLenght < 15){
	$passScore = 30;
	}else{
	$passScore = 50;
	}
	return $passScore;
}

private function convertTime($time){
	$total_time=$time;
//******************************
	$check_years = floor($total_time/(365*24*60*60));
	if($check_years !== 0){
	$years = $check_years;
	$total_time = floor($total_time%(365*24*60*60));
	}
//******************************
	$check_months = floor($total_time/(30*24*60*60));
	if($check_months !== 0){
	$months = $check_months;
	$total_time = floor($total_time%(30*24*60*60));
	}
//******************************
	$check_days = floor($total_time/(24*60*60));
	if($check_days !== 0){
	$days = $check_days;
	$total_time = floor($total_time%(24*60*60));
	}
//******************************
	$check_hours = floor($total_time/(60*60));
	if($check_hours !== 0){
	$hours = $check_hours;
	$total_time = floor($total_time%(60*60));
	}
//******************************
	$check_minutes = floor($total_time/60);
	if($check_minutes !== 0){
	$minutes = $check_minutes;
	$seconds = floor($total_time%60);
	}
//******************************

return  array("years"=>$years, "months"=>$months, "days"=>$days, "hours"=>$hours, "minutes"=>$minutes, "seconds"=>$seconds); 
}

private function calculateBreakTime($dd){

if($dd['years'] == 0 && $dd['months'] == 0  && $dd['days'] == 0 && $dd['hours'] == 0){
$poeni = 1;
}elseif($dd['years'] == 0 && $dd['months'] == 0  && $dd['days'] == 0 && $dd['hours'] < 3){
$poeni = 2;
}elseif($dd['years'] == 0 && $dd['months'] == 0  && $dd['days'] == 0 && $dd['hours'] < 6 && $dd['hours'] >= 3){
$poeni = 3;
}elseif($dd['years'] == 0 && $dd['months'] == 0  && $dd['days'] == 0 && $dd['hours'] < 12 && $dd['hours'] >= 6){
$poeni = 4;
}elseif($dd['years'] == 0 && $dd['months'] == 0  && $dd['days'] == 0 && $dd['hours'] < 24  && $dd['hours'] >=12 ){
$poeni = 5;
}elseif($dd['years'] == 0 && $dd['months'] == 0  && $dd['days'] < 2  ){
$poeni = 6;
}elseif($dd['years'] == 0 && $dd['months'] == 0  && $dd['days'] < 8  && $dd['days'] >= 2  ){
$poeni = 7;
}elseif($dd['years'] == 0 && $dd['months'] == 0  && $dd['days'] < 15  && $dd['days'] >= 8){
$poeni = 8;
}elseif($dd['years'] == 0 && $dd['months'] == 0  && $dd['days'] < 22  && $dd['days'] >= 15){
$poeni = 9;
}elseif($dd['years'] == 0 && $dd['months'] == 0  && $dd['days'] < 30  && $dd['days'] >= 22){
$poeni = 10;
}elseif($dd['years'] == 0 && $dd['months'] > 2 ){
$poeni = 15;
}elseif($dd['years'] == 0 && $dd['months'] >= 2  && $dd['months'] < 3){
$poeni = 20;
}elseif($dd['years'] == 0 && $dd['months'] >= 3  && $dd['months'] < 5){
$rezultat = "strong level 2";
$poeni = 25;
}elseif($dd['years'] == 0 && $dd['months'] >= 5  && $dd['months'] < 8){
$poeni = 30;
}elseif($dd['years'] == 0 && $dd['months'] >= 8  && $dd['months'] < 12){
$poeni = 50;
}elseif($dd['years'] < 2 && $dd['years'] > 0){
$poeni = 60;
}elseif($dd['years'] >=2   && $dd['years'] < 4){
$poeni = 70;
}elseif($dd['years'] >= 4  && $dd['years'] < 9){
$poeni = 80;
}elseif($dd['years'] >= 9  && $dd['years'] < 30){
$poeni = 90;
}elseif($dd['years'] > 30 ){
$poeni = 100;
}
return $poeni;
}


public function CalculateStrenght(){

$charTOarry = $this->ChrRepeat();

$chrex = $this->ChrExist();

	$SumEntropy= $chrex['entropySum'];

	$SumPoints = $chrex['poitsSum'];

$passLen = $this->passLenght();


//=======================
	$brojkomb =pow($chrex['usedChr'], $this->passLenght); 

	$vreme = $brojkomb/$this->brojpokusaja;

	$breakTime = $this->convertTime($vreme);

	$timeBreakPoints = $this->calculateBreakTime($breakTime);

		
//********************ENTROPIJA*************************************		
	$PairsAfirst = $this->firstLetter_pairs_Entropy();
			
	$passEntropyFull =  round($this->passLenght*( log($chrex['usedChr']) /log(2) ), 2);
	$passEntropyALLCHAR = round($this->passLenght*( log(94) /log(2) ), 2);

	$sumEntropy = ($SumEntropy + $passEntropyFull + $PairsAfirst + $passEntropyALLCHAR)/4;
	
		if($sumEntropy < 28){
		$entropyPoints = 10;
		}elseif($sumEntropy > 28 || $sumEntropy < 35){
		$entropyPoints = 20;
		}elseif($sumEntropy > 36 || $sumEntropy < 59){
		$entropyPoints = 50;
		}elseif($sumEntropy > 60  || $sumEntropy < 127){
		$entropyPoints = 100;
		}elseif($sumEntropy > 128 ){
		$entropyPoints = 150;
		}
$repeatNegative = $charTOarry['RepeatLettersPoints'] * -5;
//********************ENTROPIJA*************************************		
$final = ((($timeBreakPoints + $SumPoints + $PairsAfirst + $passLen + $repeatNegative)/5 ) + $entropyPoints)/2;
	

	if($final < 20){
	$strong = 'weak';
	}elseif($final >= 20 && $final < 30){
	$strong = 'medium';
	}elseif($final >= 30 && $final <= 35){
	$strong = 'strong';
	}elseif($final > 35){
	$strong = 'extra strong';
	}

	//$graph = round($final * 2.85);
  $graph = $final;
	
			//return $this->password.'<br>'.$graph.' - '.$strong;
      return $graph;
}


}

class passStrenght{
  public function numeric($pass)
  {
    $passMeter = new classPasswordMeter($pass);
    $aaa = $passMeter->CalculateStrenght();
    $aaa = round($aaa * 2.85);
    return $aaa;
  }
  
  public function text($pass)
  {
    $passMeter = new classPasswordMeter($pass);
    $final = $passMeter->CalculateStrenght();
    if($final < 20){
  	$strong = 'weak';
  	}elseif($final >= 20 && $final < 30){
  	$strong = 'medium';
  	}elseif($final >= 30 && $final <= 35){
  	$strong = 'strong';
  	}elseif($final > 35){
  	$strong = 'extra strong';
  	}
    return $strong;
  }
  

}

?>