from sqlalchemy import create_engine, Column, Integer, String, ForeignKey
from sqlalchemy.orm import relationship, sessionmaker, declarative_base

Base = declarative_base()

class Cliente(Base):
    __tablename__ = 'clientes'

    id = Column(Integer, primary_key=True)
    nome = Column(String, nullable=False)
    email = Column(String, nullable=False)
    enderecos = relationship('Endereco', back_populates='cliente', cascade='all, delete-orphan')

    def __repr__(self):
        return f'<Cliente(id={self.id}, nome={self.nome}, email={self.email})>'

class Endereco(Base):
    __tablename__ = 'enderecos'

    id = Column(Integer, primary_key=True)
    cliente_id = Column(Integer, ForeignKey('clientes.id'))
    rua = Column(String, nullable=False)
    cidade = Column(String, nullable=False)
    estado = Column(String, nullable=False)
    cep = Column(String, nullable=False)
    cliente = relationship('Cliente', back_populates='enderecos')

    def __repr__(self):
        return f'<Endereco(id={self.id}, rua={self.rua}, cidade={self.cidade}, estado={self.estado}, cep={self.cep})>'

def criar_banco():
    engine = create_engine('sqlite:///clientes.db')
    Base.metadata.create_all(engine)
    return engine

def main():
    engine = criar_banco()
    Session = sessionmaker(bind=engine)
    session = Session()

    # Exemplo de criação de cliente e endereço
    cliente = Cliente(nome='João Silva', email='joao@email.com')
    endereco = Endereco(rua='Rua das Flores', cidade='Rio de Janeiro', estado='RJ', cep='12345-678', cliente=cliente)

    session.add(cliente)
    session.add(endereco)
    session.commit()

    # Consulta com junção
    clientes_com_enderecos = session.query(Cliente).join(Endereco).all()
    for c in clientes_com_enderecos:
        print(c, c.enderecos)

    session.close()

if __name__ == '__main__':
    main()
