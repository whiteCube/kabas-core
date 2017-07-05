<style>

* { padding: 0; margin: 0; box-sizing: border-box; }
html, body { height: 100%; text-align: center; }

body:before {
    content: '';
    display: inline-block;
    height: 100%;
    vertical-align: middle;
    width: 0;
}

.sro {
    position: absolute;
    top: -99999px;
    left: -99999px;
}

.error {
    font-family: 'Open Sans', sans-serif;
    color: #485151;
    vertical-align: middle;
    width: 650px;

    font-size: 15px;
    display: inline-block;
    margin: 60px auto;
    text-align: left;
    line-height: 24.3px;
}

.error__heading {
    font-size: 24.3px;
    margin-bottom: 0.5em;
}

.error__isShowing {
    display: none;
}

.error__isShowing + .error__more {
    opacity: 0;
    transform: translateY(-5px);
}

.error__isShowing:checked + .error__more {
    opacity: 1;
    transform: none;
    pointer-events: all;
}

.error__img {
    width: 150px;
    float: left;
    margin-top: -50px;
    margin-right: 50px;
}

.error__showmore {
    cursor: pointer;
    color: #C95951;
    user-select: none;
    display: inline-block;
    vertical-align: middle;
}

.error__tooltipContainer {
    display: inline;
    position: relative;
    margin-left: 5px;
}

.error__more {
    margin-top: 15px;
    position: absolute;
    left: 50%;
    margin-left: -300px;
    width: 600px;
    padding: 20px 25px 25px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    transition: opacity .2s cubic-bezier(0.250,  0.460, 0.450, 0.940), transform .2s cubic-bezier(0.250,  0.460, 0.450, 0.940);
    pointer-events: none;
}

.error__more:before {
    content: '';
    display: block;
    width: 0;
    height: 0;
    border: 8px solid transparent;
    border-bottom-color: #C95951;
    position: absolute;
    bottom: 100%;
    left: 50%;
    margin-left: -8px;
}

.error__classname {
    font-weight: 700;
    display: inline-block;
}

.error__hint {
    margin-top: 10px;
}

.error__link {
    text-decoration: none;
    color: #C95951;
}

.error__report {
    display: inline-block;
    margin-top: 10px;
}

.error__code {
    
}

.error__path {
    background: #f1f1f1;
    padding: 2px 7px;
    border-radius: 3px;
    font-size: 13.3px;
    display: inline-block;
    margin-left: 10px;
    font-family: 'Roboto Mono', monospace;
}

</style>