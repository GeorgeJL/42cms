<?php                         
class ErrorText
{   //this class contains the text of errors to be displayed-if is not translation complete, keep there english text (or the text of similar language-e.g. if is not Czech translation complete, keep there Slovak text)
  public $text=array( 
  
    'default'=>'There was an error',
            0=>'<br /><b>This is not error, just test</b><br />',
            1=>'<br /><b>ERROR: The required template was not found</b><br />',
            2=>'<br /><b>ERROR: Thare is something wrong with your session</b><br />',
            3=>'<br /><b>ERROR: Thare is something wrong with your cookies</b><br />',
            4=>'Something wrong with reCAPTCA',
            5=>'You must enter captcha',
            6=>'You have entered invalid username or password',
            7=>'Your account has not been activated yet. <br />To activate your account, click on activation link in your email.',
            8=>'--DEFAULT--',
            9=>'There was an error. Check if you have copied the invitation link correctly.',
           10=>'--DEFAULT--',
           11=>'There was an error. Check if you have copied the activation link correctly.',
           12=>'Your account was probably already activated. Try to Log In',  
           13=>'There was an error. Check if you have copied the password recovery link correctly.',
           14=>'--DEFAULT--',
           15=>'You have entered invalid password',
           16=>'--DEFAULT--'
            );
}
?>