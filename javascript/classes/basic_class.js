/* basic example of a javascript class */

/* create the class */
var nameOfClass = function(){};

nameOfClass.prototype = {

  firstClassVariable: 'This is a basic example',
  secondClassVariable: 'JS objects rule!',

  firstFuncName: function(){
      // function code..
      this.textVariable = 'Var value return from class';
      secondFuncName(this.textVariable);
  },
  
  secondFuncName: function(varinput){
      return( this.varinput );
  }
}

/* Use the class */
window.onload = function(){
  // create instance
  var newInstance = new nameOfThisClass();
  // use instance
  var varFromClass = newInstance.firstFuncName();
}
