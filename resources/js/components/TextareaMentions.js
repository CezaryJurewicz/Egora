import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import { MentionsInput, Mention } from "react-mentions";

import defaultStyle from './../res/defaultStyle'
import defaultMentionStyle from './../res/defaultMentionStyle'

class TextareaMentions extends React.Component {
    constructor(props) {
        super(props)
        
        this.state = {
            name: props.name ? props.name : '',
            id: props.id ? props.id : '',
            value: props.value ? props.value : '',
            placeholder: props.placeholder ? props.placeholder : ''
        };
    }
    
    handleCommentChange = e => {
        this.setState({
          comment: e.target.value
        });
    };
    
    onChange = (ev, newValue) => {
        this.setState({ value: newValue });
    };
    
    fetchUsers(query, callback) {
        if (!query) return;
        
        axios.get(`/api/leads/my?q=${query}`, { responseType: 'json' })
        .then(result =>
              Object.values(result.data.result).map(user => ({ display: user.name, id: user.name, hash: user.hash }))
        )
        .then(callback);
    }
    
    render() {
        return (
            <div className="scrollable">
              <MentionsInput
                id={this.state.id}
                name={this.state.name}
                value={this.state.value}
                onChange= {this.onChange}
                style={defaultStyle}
                placeholder={this.state.placeholder}
                a11ySuggestionsListLabel={"Suggested mentions"}
                rows={5}
                allowSuggestionsAboveCursor={true}
                customSuggestionsContainer={(children)=><div><div style={{fontSize:"14px", fontWeight: "800", padding: "5px 15px", textAlign: "center", backgroundColor: "#ececec"}}>Your leads:</div>{children}</div>}
              >
                <Mention
                  displayTransform={name => `{${name}}`}
                  trigger="{"
                  data={this.fetchUsers}
                  style={defaultMentionStyle}
                />
              </MentionsInput>
            </div>
            );
    }
}

export default TextareaMentions;

var elm = document.querySelectorAll(".textarea-mentions");
if (elm) {
    elm.forEach(function(e) {
        var name = e.getAttribute('name');
        var id = e.getAttribute('idName');
        var value = e.getAttribute('value');
        var placeholder = e.getAttribute('placeholder');
        var onChange = e.getAttribute('onChange');
        
        ReactDOM.render(<TextareaMentions name={name} id={id} value={value} placeholder={placeholder} onChange={onChange} />, e);
    });
};
