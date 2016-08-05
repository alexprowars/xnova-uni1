var sm = new Array("adolf",20,20,  "am",18,23,  "angel",20,25,  "angl",88,28,  "aplause",31,25,  "baby",20,22, "boxing",30,30,  "bye",26,18,  "censored",48,18,  "crazy",28,28,  "dollar",15,15,  "drink",51,28,  
"duel",100,34,  "evil",20,18,  "face1",18,18,  "face2",19,19,   "face5",18,28,  "fingal",18,18,  "friday",58,30,  "fuck",80,26,  "fuu",20,20,  "girl",18,18,  
"goodnigth",33,33,  "gun1",48,22,  "gun2",48,22,  "gun_1",54,19,  "ha",34,26,  "happy",20,20,  "heart",19,19,  "hello",28,30,  "helloween",18,18,  
"help",35,25,  "hummer",41,28,  "hummer2",30,26,  "ill",20,20,  "inlove",20,20,  
"invalid",76,25,  "jack",55,18,  "jedy",50,26,  "kill",120,20,  "killed",38,24,  "king",25,27,  
"kiss2",47,27, "knut",54,20,  "lick",20,20, "lips",23,15, "lol",32,20, 
"loo",24,26,  "matrix",83,18, "med",65,31, "mediana",74,30,
 "roze",30,26, "mol",30,30, "ninja",45,45, "nunchak",40,28, "ogo",26,24, "pare",47,18, "police",20,22, "prise",31,26, "punk",31,28,
 "ravvin",24,29, "rip",25,18, "rupor",41,22, "scare",20,21, "shut",49,23,
 "sleep",38,23, "song",35,20, "strong",32,20, "terminator",52,25, "training",40,30, "user",60,23,
 "wall",51,26, "rofl",28,23, "hunter",48,38, "nosex",54,49, "bratan",52,28, "diskot",57,26,
 "jedy1",100,40, "vglaz",60,22, "duet",52,29, "ff",32,20, "smoke",33,28, "bita",26,22, "eat",42,36, "perec",33,35, "noperec",33,35, "popec",47,28, "popope",50,44,
 "morpeh",41,31, "vistre",43,27, "lethik",50,31, "naem",69,37, "pirat",42,42,
 "baraban",30,50, "klizma",42,25, "yy",21,18, "arbuz",50,35, "gamer2",37,27,
 "pulemet",49,28, "good2",35,25, "negative",30,28, "quiet",24,23, "ball",37,45, "pooh",33,36,
 "vv",55,24, "tank",61,50, "fig1",18,18, "sisi",37,37, "spam2",153,40);

var smilesimgpath='<IMG border=0 src="/images/smile/';

function Text (txt) {

	var i,j=0;
	for (i = 0; i < sm.length; i += 3) {
		while(txt.indexOf(':'+sm[i]+':') >= 0) {
			txt = txt.replace(':'+sm[i]+':', smilesimgpath + sm[i] + '.gif" ' + 'width='+sm[i+1]+' height='+sm[i+2]+sm[i]+'\')">');
		}
	}

	document.write ( txt );

}