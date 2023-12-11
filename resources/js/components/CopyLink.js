import React from 'react';
import ReactDOM from 'react-dom';

class CopyBtn extends React.Component {
    constructor(props) {
        super(props)

        this.state = {
            value: props.value ? props.value : '',
            idea_text: props.idea_text ? props.idea_text.innerText : '',
            idea_text_elm: props.idea_text ? props.idea_text : '',
            idea_text_id: props.idea_text_id ? props.idea_text_id : '',
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

    copyIdeaInvitationToClipboard = () => {
        var text = "I support this idea in Egora.\n"
                + "What do you think about it?\n"
                + "Will you support it?"
                + "\n\n"
                + this.state.idea_text
                + "\n\n"
                + this.state.value
        
        this.copyText(text);
    }
    
    copyIdeaAndLinkToClipboard = () => {
        var text = ""
                + this.state.idea_text
                + "\n\n"
                + this.state.value
        
        this.copyText(text);
    }
    
    copyCodeToClipboard = () => {
        var text = "I support this idea in Egora.\n"
                + "What do you think about it?\n"
                + "Will you support it?\n\n"
                + "#Egora #democracy #philosophy #IntelligentDemocracy #InternationalLogicParty\n" + this.state.value
        
        this.copyText(text);
    }
    
    copyLinkToClipboard = () => {
        this.copyText(this.state.value);
    }

  render() {
      
    return (
        <div className="row">
            <div className="col-md-4 text-md-center">
                <button onClick={() => this.copyIdeaInvitationToClipboard()} className="btn btn-sm btn-primary col-md-10 mb-1">
                  Copy Idea Invitation
                </button>
            </div>
            <div className="col-md-4 text-md-center">
                <button onClick={() => this.copyIdeaAndLinkToClipboard()} className="btn btn-sm btn-primary col-md-10 mb-1">
                  Copy Idea & Link
                </button>
            </div>
            <div className="col-md-4 text-md-center">
                <button onClick={() => this.copyLinkToClipboard()} className="btn btn-sm btn-primary col-md-10 mb-1">
                  Copy Idea Link
                </button>
            </div>
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

var elem = document.getElementById('copyLink');
if (elem) {
    var value = elem.getAttribute('value');
    var idea_text_id = elem.getAttribute('idea_text_id');
    var idea_text = document.getElementById(idea_text_id);
    
    ReactDOM.render(<CopyBtn value={ value } idea_text={ idea_text } idea_text_id={ idea_text_id } />, elem);
}
