import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios'
import Diff from './Diff'

class IdeaText extends React.Component {
    constructor(props) {
        super(props)
        
        this.state = {
            text: props.text ? props.text : '',
            html: props.html ? props.html : '',
        }
    }
    
    changeContent = newText => {
        this.setState({
            text: newText
        });
        
        let el = document.getElementById("idea-card"); 
        el && el.scrollIntoView(); 
    };
    
    saveText = () => {
        return this.props.text;
    };
    
    ideaId = () => {
        return this.props.ideaid;
    };
    
    render() {
        return (
            <div>
                <div className="card-body">
                <div dangerouslySetInnerHTML={{ __html: this.state.text }} />
                </div>

                <div className="card-footer pt-4 pb-4">
                    <div className="row" id="source-derivative" dangerouslySetInnerHTML={{__html: this.state.html}} />
                    <Diff oldText={this.saveText} ideaId={this.ideaId()} replace={this.changeContent} />
                </div>
            </div>
        )
    }
};

export default IdeaText;
    
var e = document.getElementById('idea-text');
if (e) {
    var div = document.getElementById('idea-div');
    var text = e.innerHTML;
    var ideaid = div.getAttribute('ideaid');
    var s = document.getElementById('source-derivative');
    var html = s.innerHTML;
    ReactDOM.render(<IdeaText text={text} ideaid={ideaid} html={html} />, div);
}

    