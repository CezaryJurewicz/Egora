export default {
    control: {
      backgroundColor: '#fff',
      fontSize: '0.9rem',
      fontWeight: 'normal',
    },

    input: {
      overflow: 'auto',
      height: 130,
      color: '#495057',
      borderRadius: '0.25rem'
    },
    
    highlighter: {
      boxSizing: 'border-box',
      overflow: 'hidden',
      height: 130,
    },

    '&multiLine': {
      control: {
        fontFamily: 'inherit',
      },
      highlighter: {
        padding: 9,
        border: '1px solid transparent',
      },
      input: {
        padding: 9,
        border: '1px solid silver',
      },
    },

    '&singleLine': {
      display: 'inline-block',
      width: 180,

      highlighter: {
        padding: 1,
        border: '2px inset transparent',
      },
      input: {
        padding: 1,
        border: '2px inset',
      },
    },

    suggestions: {
      list: {
        ':before': {
            left: '-5px',
            content: "123123",
        },
        backgroundColor: 'white',
        border: '1px solid rgba(0,0,0,0.15)',
        fontSize: 13,
      },
      item: {
        padding: '5px 15px',
        borderBottom: '1px solid rgba(0,0,0,0.15)',
        '&focused': {
          backgroundColor: '#cee4e5',
        },
      },
    },
}
