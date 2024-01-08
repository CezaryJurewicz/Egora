import React from 'react';
import ReactDOM from 'react-dom';

class SimpleCopy extends React.Component {
    constructor(props) {
        super(props)

        this.state = {
            value: props.value ? props.value : '',
            btn_title: props.btn_title ? props.btn_title : 'Copy',
            copySuccess: false
        }
    }
    
    // Credits: https://stackoverflow.com/questions/400212/how-do-i-copy-to-the-clipboard-in-javascript
    fallbackCopyTextToClipboard = (text) => {
        var textArea = document.createElement("textarea");
        textArea.value = text;

        // Avoid scrolling to bottom
        textArea.style.top = "0";
        textArea.style.left = "0";
        textArea.style.position = "fixed";

        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            this.setState({copySuccess: true})
            // console.log('Fallback: Copying text command was ' + msg);
          
            setTimeout(()=>{
                this.setState({copySuccess: false})
            }, 2000);
        } catch (err) {
          console.error('Fallback: Oops, unable to copy', err);
        }

        document.body.removeChild(textArea);
    }
    
    copyText = (text) => {
        if (!navigator.clipboard) {
          this.fallbackCopyTextToClipboard(text);
          return;
        }
        navigator.clipboard.writeText(text).then(function() {
            console.log('Async: Copying to clipboard was successful!');
            this.setState({copySuccess: true})
            
            setTimeout(()=>{
                this.setState({copySuccess: false})
            }, 2000);
        }, function(err) {
          console.error('Async: Could not copy text: ', err);
        });
    }

    copyLinkToClipboard = () => {
        this.copyText(this.state.value);
    }

  render() {
      
    return (
        <div>
                <button onClick={() => this.copyLinkToClipboard()} className="btn btn-sm btn-primary btn-block">
                  { this.state.btn_title }
                </button>
          {
            this.state.copySuccess ?
            <div style={{"color": "green"}}>
              Success!
            </div> : null
          }
        </div>
    )
  }
};

export default SimpleCopy;

var elem = document.getElementById('simpleCopy');
if (elem) {
    var value = elem.getAttribute('value');
    var btn_title = elem.getAttribute('btn_title');
    
    ReactDOM.render(<SimpleCopy value={ value } btn_title={ btn_title } />, elem);
}

var buttons = document.querySelectorAll("div.simpleCopy");
if (buttons) {
    buttons.forEach(function(elem) {
        var value = elem.getAttribute('value');
        var btn_title = elem.getAttribute('btn_title');

        ReactDOM.render(<SimpleCopy value={ value } btn_title={ btn_title } />, elem);
    });
}
