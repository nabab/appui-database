( () => {
  return {
    props: ['source'],
    computed: {
      tableSource() {
        let r = [];
        bbn.fn.iterate(this.source.structure.keys, (a, n) => {
          r.push(bbn.fn.extend({
            name: n
          }, a));
        });
        return r;
      },
      buttons(){
        return [];
      }
    },
    methods: {
      getStateColor(row) {
        let col = false;
        if (!row.is_real) {
          col = 'red';
        } else if (!row.is_virtual) {
          col = 'purple';
        } else if (row.is_same) {
          col = 'green';
        }
        return col;
      },
      writeKey(row) {
        let col = this.getStateColor(row);
        return '<a' + (col ? ' class="bbn-' + col + '"' : '') + '>' + row.name + '</a>';
      },
      writeColInKey(row) {
        return row.columns.join(", ");
      },
      writeConstraint(row) {
        if (row.constraint) {
          return row.ref_table + '.' + row.ref_column
                 + ' &nbsp;&nbsp;&nbsp;<i class="nf nf-fa-info" title="'
                 + bbn._('Database') + ': ' + row.ref_db
                 + '\n' + bbn._('Constraint') + ': ' + row.constraint
                 + '\n' + bbn._('ON UPDATE') + ' ' + row.update
                 + '\n' + bbn._('ON DELETE') + ' ' + row.delete
                 + '"></i>';
        }
        return '-';
      }
    }
  }
})();