export default {
  init() {
	var moment = require('moment');
	var moment1 = moment("1100",'hmm').format();

	if( moment().isBefore(moment1) ){
		var searchMask = "hello";
		var regEx = new RegExp(searchMask, "ig");
		var replaceMask = "Good Morning";
		document.body.innerHTML = document.body.innerHTML.replace(regEx, replaceMask);
	}
  },
  finalize() {

  },
};