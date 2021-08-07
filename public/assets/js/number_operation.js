 var floatCalc = function (a, b) {
        a = a + '', b = b + '';
        var aNum = a.indexOf('.'),
            bNum = b.indexOf('.'),
            aSum,
            bSum,
            resultNum,
            inta,
            intb;
 
        aSum = aNum < 0 ? 0 : a.split('.')[1].length;
        bSum = bNum < 0 ? 0 : b.split('.')[1].length;
        resultNum = aSum > bSum ? aSum : bSum;
 
        inta = aNum < 0 ? Number(a + (Math.pow(10, resultNum) + '').replace('1', '')) : (function () {
            a = a.replace('.', '');
            a = resultNum == aSum ? a : a + (Math.pow(10, resultNum - aSum) + '').replace('1', '');
            return Number(a);
        }())
 
        intb = bNum < 0 ? Number(b + (Math.pow(10, resultNum) + '').replace('1', '')) : (function () {
            b = b.replace('.', '');
            b = resultNum == bSum ? b : b + (Math.pow(10, resultNum - bSum) + '').replace('1', '');
            return Number(b);
        }())
 
        return {
            a: inta,
            b: intb,
            num: resultNum
        };
    }
    //加法
    Number.prototype.add = function (n) {
        var o = floatCalc(this, n);
        return (o.a + o.b) / Math.pow(10, o.num);
    }
    //减法
    Number.prototype.minus = function (n) {
        var o = floatCalc(this, n);
        return (o.a - o.b) / Math.pow(10, o.num);
    }
    //乘法
    Number.prototype.subtract = function (n) {
        var o = floatCalc(this, n);
        return (o.a * o.b) / Math.pow(10, o.num * 2);
    }
    //除法
    Number.prototype.divide = function (n) {
        var o = floatCalc(this, n);
        return (o.a / o.b);
    }