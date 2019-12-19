import React from 'react';
import ReactDOM from 'react-dom';

import DatePicker from "react-datepicker";

import "react-datepicker/dist/react-datepicker.css";

class DateInput extends React.Component {
  state = {
    startDate: new Date()
  };

  render() {
    const { startDate } = this.state;
    var minDate = new Date();
    var maxDate = new Date();
    maxDate.setDate(minDate.getDate()+46);
    
    const ref = React.createRef();
    const CustomInput = React.forwardRef(({ value, onClick, onChange }, ref) => (
        <input id="date" type="text" className={this.props.cssClass} name="date" value={value} onChange={onChange} onClick={onClick}/>
    ));
    
    return <DatePicker 
    customInput={<CustomInput />}
    selected={startDate} 
    onChange={this.handleChange} 
    dateFormat="dd/MM/yyyy"
    minDate={ minDate }
    maxDate={ maxDate }
    />;
  }

  handleChange = startDate => {
    this.setState({
      startDate
    });
  };
};

if (document.getElementById('dateInput')) {
    var cssClass = document.getElementById('dateInput').getAttribute('cssClass');
    ReactDOM.render(<DateInput cssClass={cssClass} />, document.getElementById('dateInput'));
}