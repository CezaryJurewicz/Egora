import React from 'react';
import ReactDOM from 'react-dom';

class CopyBtn extends React.Component {
    constructor(props) {
        super(props)

        this.state = {
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
            console.log('Fallback: Copying text command was ' + msg);
          
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

    copyCodeToClipboard = () => {
        var text = "I support this idea in Egora: " + window.location.href + "\n"
                + "What do you think about it?\n"
                + "Will you support it?\n\n"
                + "***********************\n"
                + "Egora, “The Worldwide Stock-Market of Ideas”, enables everyone to\n"
                + "– develop their own political philosophy out of various ideas,\n"
                + "– determine which ideas are most strongly supported by the people, and\n"
                + "– find the true representatives of the public will to elect them into public office."
        
        this.copyText(text);
    }

  render() {
      
    return (
        <div class="row">
          <button onClick={() => this.copyCodeToClipboard()} class="btn btn-sm btn-primary col-md-12">
            Copy Link
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

export default CopyBtn;

if (document.getElementById('copyLink')) {
    ReactDOM.render(<CopyBtn />, document.getElementById('copyLink'));
}
