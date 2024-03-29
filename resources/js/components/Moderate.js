import React from 'react';
import ReactDOM from 'react-dom';

class CallAPI extends React.Component {
    constructor(props) {
        super(props)
        
        this.state = {
            vote: props.vote ? props.vote : '',
            comment: props.comment ? props.comment : '',
            action_keep: props.action_keep ? props.action_keep : '',
            action_delete: props.action_delete ? props.action_delete : '',
            score: props.score ? parseInt(props.score) : '',
            callSuccess: false,
            disableKeep: false,
            disableDelete: false,
        }
    }
    
    callClick = (action) => {
        window.axios.post(action)
            .then(r => {
                if (r.status === 200) {
                    this.setState({callSuccess: true});
                    if (action == this.state.action_keep) {
                        this.setState({vote: 1});
                        this.setState({disableKeep: true});
                        this.setState({score: this.state.score+1});
                    } else if (action == this.state.action_delete) {
                        this.setState({vote: -1});
                        this.setState({disableDelete: true});
                        this.setState({score: this.state.score-1});                        
                    }
                    
                    if (typeof(r.data) !== 'undefined' && typeof(r.data.score) !== 'undefined') {
                        this.setState({score: r.data.score});                        
                    }
                    
                    if (typeof r.data.deleted !== 'undefined') {
                        document.getElementById(this.state.comment).parentElement.parentElement.remove();                    
                    }
                } else {
                   console.log(r.status);
                }
            })
            .catch(r => {
                // console.error(r.status);
            });
    }    

    render() {
        return (
//            this.state.callSuccess ?
//            this.state.caption : 
            <span>
            [{ this.state.vote == 1 ?
            'Keep':
            <a href="#/" onClick={() => this.callClick(this.state.action_keep)}>
                Keep 
            </a> }]
            or
            [{ this.state.vote == -1 ?
            'Delete':
            <a href="#/" onClick={() => this.callClick(this.state.action_delete)}>
                Delete
            </a> }]
            this comment? { this.state.score }
            </span>
                
        )
    }
};

export default CallAPI;
    
var calls = document.querySelectorAll("span.moderate");
if (calls) {
    calls.forEach(function(e) {
        var comment = e.getAttribute('comment');
        var vote = e.getAttribute('vote');
        var action_keep = e.getAttribute('action_keep');
        var action_delete = e.getAttribute('action_delete');
        var score = e.textContent;
        
        ReactDOM.render(<CallAPI vote={vote} comment={comment} action_keep={action_keep} action_delete={action_delete} score={score} />, e);
    });
    
}

    