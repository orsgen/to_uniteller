/**
 * Validation input values before sending to server
 * @param {array} numbers  Firs pare - left operand, Second - rigth 
 * @param {string} op Math operator
 * @returns boolean
 */
function isvalid(numbers, op) {
    for(var i=0; i<numbers.length; i++) 
        if(numbers[i].length==0 && numbers[i+1].length==0) {
                if(i<2)
                    var w="Левый";
                else
                    var w="Правый";        
                alert(w + " операнд не задан");
            return false;
        }
    
    if( op=="div" && (numbers[3]==0 || numbers[3].length==0) && (numbers[4]==0 || numbers[3].length==0)) {
        alert("На ноль делить нельзя!");
        return false;
    }
    return true;
}

//Put in html-element result of math operation getted by post json
//ans - JSON
//for nicing expression as 0+-1i and so on made test some conditions
function putResponse(ans) {
    if (ans[0].status==1) {
        var txt = "";
        if(ans[1].x!=0) 
            txt = ans[1].x;
        if(ans[1].y!=0) {
                if(ans[1].y<0) {
                    if(ans[1].y==-1)
                        txt += "-i";    
                    else
                        txt += ans[1].y+"i";
                } else {
                    if(ans[1].x==0)
                        var op = "";
                    else
                        var op = "+";
                    if(ans[1].y!=1)
                        txt += op+ans[1].y+"i";
                    else 
                        txt +=op+"i";
                }
        } else
            if(txt.length==0)
                txt = 0;
        document.getElementById("putres").innerHTML =  txt;
    } else {//==0
        var r = "Обнаружены ошибки:";
        for( var i=1; i<ans.length; i++)
            r += "("+ans[i].errno+") :: "+ans[i].errmsg;
        alert(r);
    } 
}

/**
 * Check data validation. Send ajax post. Get json result and call PutResult()
 */
function choiced() {
    const op = document.getElementById("ops").value;
    const numbers = [document.getElementById("x1").value, document.getElementById("y1").value,
        document.getElementById("x2").value, document.getElementById("y2").value];

    if(isvalid(numbers,op)) {
        let request = new XMLHttpRequest();
        const url="/complex.php";
        let params = "op=" + op;
        for(i=1;i<3;i++)
            params += "&x" + i + "=" + numbers[2*(i-1)]
                + "&y" + i + "=" + numbers[2*i-1];
        request.open("POST", url, true);
        request.responseType = 'json';
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        request.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                putResponse(this.response);
            } 
        }; 

        request.send(params);

    }
}