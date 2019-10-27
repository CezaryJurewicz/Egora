import React from 'react';
import ReactDOM from 'react-dom';

class RegisterForm extends React.Component {
    componentDidMount() {
        var el = document.getElementById('name');
        el.addEventListener('input', this.myHandler);
    }
    
    componentWillUnmount() {
        document.body.removeEventListener('input', this.myHandler);
    }
    
    myHandler() {
        document.getElementById('regName').textContent = document.getElementById('name').value;
        document.getElementById('regName2').textContent = document.getElementById('name').value;
    }
    
    render() {
        return (
            '(your name)'
        );
    }
};

export default RegisterForm;

if (document.getElementById('regName')) {
    ReactDOM.render(<RegisterForm />, document.getElementById('regName'));
}
