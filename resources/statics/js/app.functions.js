function string_to_slug(str) {

      str = str.replace(/^\s+|\s+$/g, ""); // trim
      str = str.toLocaleLowerCase("tr-TR");

      // remove accents, swap ñ for n, etc
      var from = "çöışüğ";
      var to = "coisug";

      for (var i = 0, l = from.length; i < l; i++) {
        str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
      }

      str = str
        .replace(/[^a-z0-9 -]/g, "-") // remove invalid chars
        .replace(/\s+/g, "-") // collapse whitespace and replace by -
        .replace(/-+/g, "-"); // collapse dashes

      return str;
}


$('.phone_number', '.form-horizontal').keydown(function (e) {
    var key = e.which || e.charCode || e.keyCode || 0;
    $phone = $(this);

    // Don't let them remove the starting '('
    if ($phone.val().length === 1 && (key === 8 || key === 46)) {
        $phone.val('('); 
        return false;
    } 
    // Reset if they highlight and type over first char.
    else if ($phone.val().charAt(0) !== '(') {
        $phone.val('('+$phone.val()); 
    }

    // Auto-format- do not expose the mask as the user begins to type
    if (key !== 8 && key !== 9) {
        if ($phone.val().length === 4) {
            $phone.val($phone.val() + ')');
        }
        if ($phone.val().length === 5) {
            $phone.val($phone.val() + ' ');
        }           
        if ($phone.val().length === 9) {
            $phone.val($phone.val() + '-');
        }
    }

    // Allow numeric (and tab, backspace, delete) keys only
    return (key == 8 || 
            key == 9 ||
            key == 46 ||
            (key >= 48 && key <= 57) ||
            (key >= 96 && key <= 105)); 
})

.bind('focus click', function () {
    $phone = $(this);

    if ($phone.val().length === 0) {
        $phone.val('(');
    }
    else {
        var val = $phone.val();
        $phone.val('').val(val); // Ensure cursor remains at the end
    }
})

.blur(function () {
    $phone = $(this);

    if ($phone.val() === '(') {
        $phone.val('');
    }
});