import React from 'react';
import ReactDOM from 'react-dom';

class Invite extends React.Component {
    constructor(props) {
        super(props)
        
        this.state = {
            action: props.action ? props.action : '',
            inviteSuccess: false
        }
    }
    
    inviteClick = () => {
        window.axios.post(this.state.action)
            .then(r => {
                if (r.status === 200) {
                    this.setState({inviteSuccess: true});
                } else {
                  console.log(r.status);
                }
            })
            .catch(r => {
                console.error(r.data);
            });
    }    

    render() {
        return (
            this.state.inviteSuccess ?
            'Invited' : 
            <button onClick={() => this.inviteClick()}  type="submit" class="btn btn-sm btn-primary col-md-12">
                Invite
            </button>
        )
    }
};

export default Invite;
    
var invites = document.querySelectorAll("div.user-invite");
if (invites) {
    invites.forEach(function(e) {
        var action = e.getAttribute('action');
        
        ReactDOM.render(<Invite action={action} />, e);
    });
    
}

    