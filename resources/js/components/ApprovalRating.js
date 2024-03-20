import React from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import { Chart } from "react-google-charts";

export const options = {
    legend: { position: 'none'},
    theme: "maximized",
    enableInteractivity: true,
//    axisFontSize : 0,
    tooltip: {
        isHtml: false,
//        trigger: 'none'
    },
    vAxis: {
        minValue: 0, //1
        maxValue: 0,
        baselineColor: '#fff',
//        gridlineColor: '#fff',
        textPosition: 'none',
        gridlines: {
            count:5,
//            color: 'transparent',
//            interval: 0
        }
    },
    hAxis: {
        minValue: 0,
        maxValue: 0,
        baselineColor: 'transparent',
//        gridlineColor: '#fff',
        textPosition: 'none',
        gridlines: {
            count:0,
//            color: 'transparent',
//            interval: 0
        }
    },
    
};

class ApprovalRating extends React.Component {
    constructor(props) {
        super(props)
        
        this.state = {
            id: props.id ? props.id : '',
            vote: 0,
            result: {avg:0, cols:[]},
            options: options,
            data : [
                ["vote", "avg", { role: "style" }, { role: "tooltip" } ],
                ["-3", 0, "#ff0000", ''],
                ["-2", 0, "#ff0000", ''],
                ["-1", 0, "#ff0000", ''],
                ["0", 0, "#ff0000", ''],
                ["1", 0, "#ff0000", ''],
                ["2", 0, "#ff0000", ''],
                ["3", 0, "#ff0000", '']
            ]
        };
        
        this.getApprovalRating(this.state.id)
    }
    
    onOptionChange = e => {
        this.setState({vote:e.target.value});
    }

    
    chartEvents = [
    {
        eventName: "select",
        callback: ({ chartWrapper }) => {
          const chart = chartWrapper.getChart();
          const selection = chart.getSelection();
          if (selection.length === 1) {
            const [selectedItem] = selection;
            const dataTable = chartWrapper.getDataTable();
            const { row, column } = selectedItem;

            const value = dataTable ?.getValue(row, column-1);
            this.setState({
                vote: value
            });
          }
        },
      },
    ];    
    
    getApprovalRating = (id) => {
        if (!id) return;
        
        axios.get(`/api/approval_rating?id=${id}`, { responseType: 'json' })
        .then(({ data }) => {
            this.setState({
              result: data
            });
            
            var arr = data.cols.map((col) => (
                [col.score, col.avg, col.color, col.tooltip]
            ));
            
            arr.unshift(this.state.data[0])
            
            this.setState({
                data: arr
            })
            
        });
    }
    
    clickVote = () => {
        
        axios.post(`/api/approval_rating`, {
                id: this.state.id,
                score: this.state.vote
        }).then(({ data }) => {
            this.getApprovalRating(this.state.id);
        });
    };
        
    render() {
        return (
            <div className="approval_rating_chart">
                <div className="row">
                    <div className="col text-left">
                    Approval Rating: {this.state.result.avg} 
                    </div>
                </div>

                <Chart chartType="ColumnChart" 
                    width="100%" height="200px" 
                    data={this.state.data} 
                    options={options}
                    chartEvents={this.chartEvents}
                />

                <div className="row text-center pl-4 pr-2">
                    {this.state.result.cols.map((col, index) => (
                        <div className="col p-0" key={index}>
                            <div className="form-check form-check-inline">
                                <label className="form-check-label" htmlFor={"chart_radio"+col.score}>{col.score}</label>
                                { this.state.result.vote_allowed ?
                                    <input className="form-check-input mr-0" type="radio" name="score" id={"chart_radio"+col.score} value={col.score}
                                        onChange={this.onOptionChange} 
                                        checked={this.state.vote == col.score}/>
                                : null }
                            </div>
                        </div>
                    ))}
                </div>

                { this.state.result.vote_allowed ?
                <div className="row text-center">
                    <div className="col-12">
                        <button className="btn btn-sm btn-primary col-12 col-md-4 col-lg-2" onClick={this.clickVote}>Vote</button>
                    </div>
                </div>
                : null }
            </div>
        );
    }
}

export default ApprovalRating;

if (document.getElementById('ApprovalRating')) {
    var value = document.getElementById('ApprovalRating').getAttribute('value');
    ReactDOM.render(<ApprovalRating id={ value } />, document.getElementById('ApprovalRating'));
}