

kblibrary.event.onPageLoad(function() {

	kblibrary.overlay.createModalDialogBox( {
		dialogBox : $("input"),
		displayButtons: [$("login")],
		hideButtons: [$("login-back")],
		dialogWidth: 350,
		dialogHeight: 190,
		fadeTime: 350.0,
		finalOpacity: 0.8 });

	kblibrary.overlay.createModalDialogBox( {
		dialogBox : $("input1"),
		displayButtons: [$("login1")],
		hideButtons: [$("login-back1")],
		dialogWidth: 350,
		dialogHeight: 190,
		fadeTime: 350.0,
		finalOpacity: 0.8 });

	kblibrary.overlay.createModalDialogBox( {
		dialogBox : $("promoinput"),
		displayButtons: [$("promocode")],
		hideButtons: [$("promocode-back")],
		dialogWidth: 350,
		dialogHeight: 190,
		fadeTime: 350.0,
		finalOpacity: 0.8 });
});
