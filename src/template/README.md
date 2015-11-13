# Der TemplateParser

Geht XML/HTML mit dem schnellen `XMLReader` durch und ersetzt block und inline-Elemente, indem
er den gesamten Content in JavaScript `print()` Aufrufe verpackt.

## Wie wird geparst

Der Parser geht davon aus, dass der Zeiger bereits auf dem ersten Element steht. Jede Unterinstanz 
ist jeweils

```
<!-- Instanz: I: Zeiger steht auf erstem Element -->
<div1>

<!-- Instanz I: Neues Element "div2" -> Starte Instanz II -->
    <div2>
    
<!-- Instanz II: Close Element div2 -> Code zurückgeben -->
    </div2>
    
    <div3>
    </div3>

<!-- Instanz I: EndElement gefunden -->
</div1>
```


## 




