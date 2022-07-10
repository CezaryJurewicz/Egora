import React from 'react';
import ReactDOM from 'react-dom';

import DatePicker from "react-datepicker";

import "react-datepicker/dist/react-datepicker.css";

class TimeInput extends React.Component {
  state = {
    startDate: this.props.value && (this.props.value.length > 0) ? this.parseTime(this.props.value) : null,
    caption: this.props.caption ? this.props.caption : "Time",
    name: this.props.name ? this.props.name : "time",
    id: this.props.id ? this.props.id : "time"
  };

  render() {
    const { startDate } = this.state;
    
    const ref = React.createRef();
    const CustomInput = React.forwardRef(({ value, onClick, onChange }, ref) => (
        <input id={this.state.id} type="text" className={this.props.cssClass} name={this.state.name} value={value} onChange={onChange} onClick={onClick}/>
    ));
    
    return <DatePicker 
    customInput={<CustomInput />}
    selected={startDate} 
    onChange={this.handleChange} 
    showTimeSelect
    showTimeSelectOnly
    timeCaption={this.state.caption}
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
  
  parseTime( t ) {
    if ( t == 'now') {
        return new Date();
    }
      
    var d = new Date();
    var time = t.match( /(\d+)(?::(\d\d))?\s*(p?)/ );
    
    if (time) {
        d.setHours( parseInt( time[1]) + (time[3] ? 12 : 0) );
        d.setMinutes( parseInt( time[2]) || 0 );
        return d;
    }
    
    return null;
  }
};

var calls = document.querySelectorAll("div.timeInput");

if (calls) {
    calls.forEach(function(e) {
        var id = e.getAttribute('id');
        var cssClass = e.getAttribute('cssClass');
        var name = e.getAttribute('name');
        var value = e.getAttribute('value');
        var caption = e.getAttribute('caption');
        ReactDOM.render(<TimeInput cssClass={cssClass} name={name} value={value} caption={caption} id={id} />, e);
    });    
}