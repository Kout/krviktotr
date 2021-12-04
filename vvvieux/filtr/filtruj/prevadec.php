<?php
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\" \"http://www.w3.org/TR/html4/strict.dtd\">
<html lang=\"cs\">
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">
<link rel=\"stylesheet\" type=\"text/css\" href=\"vzhled-filtruj.css\" />
<title>Převaděč</title>
</head>
<body>";
echo "<form action=\"\" method=\"post\">
  <label for=\"nadpis\">zadej text</label>
  <input type=\"text\" name=\"nadpis\" maxlength=\"64\" />
  <input type=\"submit\" value=\"vypoť\" />
  </form>";
if(!$_POST["nadpis"]){
$_POST["nadpis"] = "štěňátko je mrtvé";
}
$abeceda = array(
	"(c)" => "<img src=\"obr/abeceda/copy.png\" alt=\"©\" width=\"".$vel."\" height=\"".$vel."\" />\n",
	"#" => "<img src=\"obr/abeceda/_cerv.png\" alt=\" \" width=\"".$vel."\" height=\"".$vel."\" />\n",
    "." => "<img src=\"obr/abeceda/tecka.png\" alt=\".\" width=\"50\" height=\"50\" />\n",
         "," => "<img src=\"obr/abeceda/carka.png\" alt=\",\" width=\"50\" height=\"50\" />\n",
	 "!" => "<img src=\"obr/abeceda/vykricnik.png\" alt=\".\" width=\"50\" height=\"50\" />\n",
	 "?" => "<img src=\"obr/abeceda/otaznik.png\" alt=\".\" width=\"50\" height=\"50\" />\n",
	  "-" => "<img src=\"obr/abeceda/-.png\" alt=\"-\" width=\"50\" height=\"50\" />\n",
	  " " => "<img src=\"obr/abeceda/_.png\" alt=\" \" width=\"50\" height=\"50\" />\n",
          "a" => "<img src=\"obr/abeceda/a.png\" alt=\"a\" width=\"50\" height=\"50\" />\n",
          "b" => "<img src=\"obr/abeceda/b.png\" alt=\"b\" width=\"50\" height=\"50\" />\n",
          "c" => "<img src=\"obr/abeceda/c.png\" alt=\"c\" width=\"50\" height=\"50\" />\n",
          "d" => "<img src=\"obr/abeceda/d.png\" alt=\"d\" width=\"50\" height=\"50\" />\n",
          "e" => "<img src=\"obr/abeceda/e.png\" alt=\"e\" width=\"50\" height=\"50\" />\n",
          "f" => "<img src=\"obr/abeceda/f.png\" alt=\"f\" width=\"50\" height=\"50\" />\n",
          "g" => "<img src=\"obr/abeceda/g.png\" alt=\"g\" width=\"50\" height=\"50\" />\n",
          "h" => "<img src=\"obr/abeceda/h.png\" alt=\"h\" width=\"50\" height=\"50\" />\n",
          "i" => "<img src=\"obr/abeceda/i.png\" alt=\"i\" width=\"50\" height=\"50\" />\n",
          "j" => "<img src=\"obr/abeceda/j.png\" alt=\"j\" width=\"50\" height=\"50\" />\n",
          "k" => "<img src=\"obr/abeceda/k.png\" alt=\"k\" width=\"50\" height=\"50\" />\n",
          "l" => "<img src=\"obr/abeceda/l.png\" alt=\"l\" width=\"50\" height=\"50\" />\n",
          "m" => "<img src=\"obr/abeceda/m.png\" alt=\"m\" width=\"50\" height=\"50\" />\n",
          "n" => "<img src=\"obr/abeceda/n.png\" alt=\"n\" width=\"50\" height=\"50\" />\n",
          "o" => "<img src=\"obr/abeceda/o.png\" alt=\"o\" width=\"50\" height=\"50\" />\n",
          "p" => "<img src=\"obr/abeceda/p.png\" alt=\"p\" width=\"50\" height=\"50\" />\n",
          "q" => "<img src=\"obr/abeceda/q.png\" alt=\"q\" width=\"50\" height=\"50\" />\n",
          "r" => "<img src=\"obr/abeceda/r.png\" alt=\"r\" width=\"50\" height=\"50\" />\n",
          "s" => "<img src=\"obr/abeceda/s.png\" alt=\"s\" width=\"50\" height=\"50\" />\n",
          "t" => "<img src=\"obr/abeceda/t.png\" alt=\"t\" width=\"50\" height=\"50\" />\n",
          "u" => "<img src=\"obr/abeceda/u.png\" alt=\"u\" width=\"50\" height=\"50\" />\n",
          "v" => "<img src=\"obr/abeceda/v.png\" alt=\"v\" width=\"50\" height=\"50\" />\n",
          "w" => "<img src=\"obr/abeceda/w.png\" alt=\"w\" width=\"50\" height=\"50\" />\n",
          "x" => "<img src=\"obr/abeceda/x.png\" alt=\"x\" width=\"50\" height=\"50\" />\n",
          "y" => "<img src=\"obr/abeceda/y.png\" alt=\"y\" width=\"50\" height=\"50\" />\n",
          "z" => "<img src=\"obr/abeceda/z.png\" alt=\"z\" width=\"50\" height=\"50\" />\n",
         "á" => "<img src=\"obr/abeceda/aa.png\" alt=\"á\" width=\"50\" height=\"50\" />\n",
         "č" => "<img src=\"obr/abeceda/cc.png\" alt=\"č\" width=\"50\" height=\"50\" />\n",
         "ď" => "<img src=\"obr/abeceda/dd.png\" alt=\"ď\" width=\"50\" height=\"50\" />\n",
         "é" => "<img src=\"obr/abeceda/ee.png\" alt=\"é\" width=\"50\" height=\"50\" />\n",
        "ě" => "<img src=\"obr/abeceda/eee.png\" alt=\"ě\" width=\"50\" height=\"50\" />\n",
         "í" => "<img src=\"obr/abeceda/ii.png\" alt=\"í\" width=\"50\" height=\"50\" />\n",
         "ň" => "<img src=\"obr/abeceda/nn.png\" alt=\"ň\" width=\"50\" height=\"50\" />\n",
         "ó" => "<img src=\"obr/abeceda/oo.png\" alt=\"ó\" width=\"50\" height=\"50\" />\n",
         "ř" => "<img src=\"obr/abeceda/rr.png\" alt=\"ř\" width=\"50\" height=\"50\" />\n",
         "š" => "<img src=\"obr/abeceda/ss.png\" alt=\"š\" width=\"50\" height=\"50\" />\n",
         "ť" => "<img src=\"obr/abeceda/tt.png\" alt=\"ť\" width=\"50\" height=\"50\" />\n",
         "ú" => "<img src=\"obr/abeceda/uu.png\" alt=\"ú\" width=\"50\" height=\"50\" />\n",
         "ů" => "<img src=\"obr/abeceda/uo.png\" alt=\"ů\" width=\"50\" height=\"50\" />\n",
         "ý" => "<img src=\"obr/abeceda/yy.png\" alt=\"ý\" width=\"50\" height=\"50\" />\n",
         "ž" => "<img src=\"obr/abeceda/zz.png\" alt=\"ž\" width=\"50\" height=\"50\" />\n",
          );

$nadpis = $_POST["nadpis"];

$sekana = strtr($nadpis, $abeceda);
echo "<h2>nadpejsek:</h2>";
echo "<h2>";
print_r ($sekana);
echo "</h2>";
echo "<h2>kód:</h2>";
echo "<xmp>";
print_r($sekana);
echo "</xmp>";
echo "<h2>pojmenování PNG:</h2>";
echo "<pre>";
echo "
# => _cerv.png
(c) => copy.png
. => tecka.png
, => carka.png
! => vykricnik.png
? => otaznik.png
- => -.png
  => _.png
a => a.png
b => b.png
c => c.png
d => d.png
e => e.png
f => f.png
g => g.png
h => h.png
i => i.png
j => j.png
k => k.png
l => l.png
m => m.png
n => n.png
o => o.png
p => p.png
q => q.png
r => r.png
s => s.png
t => t.png
u => u.png
v => v.png
w => w.png
x => x.png
y => y.png
z => z.png
á => aa.png
č => cc.png
ď => dd.png
é => ee.png
ě => eee.png
í => ii.png
ň => nn.png
ó => oo.png
ř => rr.png
š => ss.png
ť => tt.png
ú => uu.png
ů => uo.png
ý => yy.png
ž => zz.png";
echo "</pre>";
echo "</body>\n</html>";
?> 
