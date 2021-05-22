import React from 'react'

const Suggestions = (props) => {
  const { onClickValue } = props;
  const options = props.results.map(r => (
    <li className="list-group-item list-group-item-action py-1" style={{ zIndex: 100 , cursor: "pointer"}} key={r.id} title={r.title} onClick={onClickValue}>
      {r.title}
    </li>
  ));
  
  return <ul className="list-group">{options}</ul>;
};

export default Suggestions