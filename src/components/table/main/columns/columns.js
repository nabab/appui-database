( () => {
  return {
    props: ['source'],
    computed: {
      tableSource(){
        let r = [];
        bbn.fn.iterate(this.source.structure.fields, (a, n) => {
          r.push(bbn.fn.extend({name: n}, a));
        });
        return r;
      }
    },
    methods: {
      getStateColor(row) {
        let col = false;
        if (!row.is_real) {
          col = 'red';
        } else if (!row.virtual) {
          col = 'purple';
        } else if (row.is_same) {
          col = 'green';
        }
        return col;
      },
      writeKeyInCol(row) {
        if (!row.key) {
          return ' ';
        }
        return '<i class="nf nf-fa-key ' + (row.key === 'PRI' ? 'bbn-yellow' : 'bbn-grey') + '"> </i>';
      },
      writeType(row) {
        if (row.type === 'int') {
          row.type += ' (<em>' + (row.signed ? '' : 'un') + 'signed)</em>';
        }
        return row.type;
      },
      writeColumn(row) {
        let col = this.getStateColor(row);
        return '<a' + (col ? ' class="bbn-' + col + '"' : '') + '>' + row.name + '</a>';
      },
      writeNull(row) {
        return row.null ? '<i class="nf nf-fa-check"> </i>' : ' ';
      },
      writeDefault(row) {
        bbn.fn.log('row.default',row.default)
        return row.default || '-';
      },
    }
  }
})();