 FingerPrint Biometric validation quiz access rule
 =============================================
 Moodle plugin that validates the user's fingerprint before attempting a quiz

 Requirements
 ------------
 - Moodle 3.2 (build 2016120500) or later.

 Installation
 ------------
 Copy and extract this repository into your Moodle /mod/quiz/accessrule directory and
 visit your Admin Notification page to complete the installation.
 Install the FPBiometric service in the students' computers and verify that it's runnig.

 Usage
 -----
 Under the extra restrictions over attempts in quiz configuration, you can set
 the requirement of fingerprint validation for that quiz.

 Exemptions
 -----
 If you want to exempt some users of the requirement, you must create a new
 system role that has permition for 'quizaccess/fpbiometric:exempt', and assign it
 to the exempted users.

 Author
 ------
 - victorcatn

 Links
 -----
 - Updates:
 - Latest code: https://github.com/victorcatn/moodle-quizaccess_fpbiometric

 Changes
 -------
 Release 0.1 :
 - Initial release.

 Release 0.2 (build 2019101202):
 - Added role based exemption.
