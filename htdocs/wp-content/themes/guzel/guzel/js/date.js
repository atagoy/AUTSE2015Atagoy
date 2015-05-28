var mydate=new Date()
var year=mydate.getYear()
if (year < 1000)
year+=1900
var day=mydate.getDay()
var month=mydate.getMonth()
var daym=mydate.getDate()
if (daym<10)
daym="0"+daym
var dayarray=new Array("воскресенье","понедельник","вторник","среда","четверг","пятница","суббота")
var montharray=new Array("января","февраля","марта","апреля","мaя","июня","июля","августа","сентября","октября","ноября","декабря")
document.write("<p>Сегодня "+daym+" "+montharray[month]+"  "+year+ " года,   "+dayarray[day]+".</p>")