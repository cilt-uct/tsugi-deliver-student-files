<?php class_exists('Template') or exit; ?>
<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
<?php foreach($styles as $style): ?>
    <link href="<?php echo $style ?>" rel="stylesheet" />
<?php endforeach; ?>
<script type="text/javascript">
    function getObj(id, arr, key) { key = key || 'id'; var o = null; $.each(groups, function (i, el) { if (el[key] == id) { o=el; return; } }); return o; };
    function cleanObj(o) { Object.keys(o).forEach(key => o[key] === undefined ? delete o[key] : {}); return o; }

    Array.prototype.sum = function (prop) {
        var total = 0
        for ( var i = 0, _len = this.length; i < _len; i++ ) {
            total += parseInt(this[i][prop]);
        }
        return total;
    }
    function formatNumber(num) {
        if (isNaN(num)) {
            return '';
        }
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1 ')
    }

    if (!String.prototype.endsWith) {
        String.prototype.endsWith = function(searchStr, Position) {
            // This works much better than >= because
            // it compensates for NaN:
            if (!(Position < this.length))
                Position = this.length;
            else
                Position |= 0; // round position
            return this.substr(Position - searchStr.length,
                                searchStr.length) === searchStr;
        };
    }
    if (!String.prototype.startsWith) {
            String.prototype.startsWith = function(searchString, position){
            return this.substr(position || 0, searchString.length) === searchString;
        };
    }
</script>