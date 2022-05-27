import React from 'react';
import ReactDOM from 'react-dom';

import DatePicker from "react-datepicker";

import "react-datepicker/dist/react-datepicker.css";

class TimeInput extends React.Component {
  state = {
    startDate: new Date()
  };

  render() {
    const { startDate } = this.state;
    
    const ref = React.createRef();
    const CustomInput = React.forwardRef(({ value, onClick, onChange }, ref) => (
        <input id="time" type="text" className={this.props.cssClass} name="time" value={value} onChange={onChange} onClick={onClick}/>
    ));
    
    return <DatePicker 
    customInput={<CustomInput />}
    selected={startDate} 
    onChange={this.handleChange} 
    showTimeSelect
    showTimeSelectOnly
    timeCaption="Time"
    timeIntervals={15}
    dateFormat="HH:mm"
    timeFormat="HH:mm"
    />;
  }

  handleChange = startDate => {
    this.setState({
      startDate
    });
  };
};

if (document.getElementById('timeInput')) {
    var cssClass = document.getElementById('timeInput').getAttribute('cssClass');
    ReactDOM.render(<TimeInput cssClass={cssClass} />, document.getElementById('timeInput'));
}