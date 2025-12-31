<?php

namespace class;

use Exception;
use PDO;
use PDOException;

/**
 * Clase Abstracta Config
 * Gestiona la carga de variables .env y la conexión PDO.
 */
abstract class Config
{
    protected readonly string $key;
    protected readonly string $secretKey;

    private $debug = true;

    private $host;
    private $user;
    private $pass;
    private $db;

    private $conexion;

    private $rutaEnv = __DIR__ . "/../.env";

    public function __construct()
    {
        $this->key = "josearriola";

        $this->loadEnv();
        $this->connect();
    }

    private function loadEnv(): void
    {
        $filePath = $this->rutaEnv;
        if (file_exists($filePath)) {
            $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0)
                    continue;

                list($name, $value) = explode('=', $line, 2);

                $this->setEnvVariable(trim($name), trim($value));
            }
        } else {
            throw new Exception(".env no encontrado." . $filePath);
        }
    }

    private function setEnvVariable($name, $value): void
    {

        switch ($name) {
            case 'DB_HOST':
                $this->host = $this->desencriptarTexto($value);
                break;
            case 'DB_USER':
                $this->user = $this->desencriptarTexto($value);
                break;
            case 'DB_PASS':
                $this->pass = $this->desencriptarTexto($value);
                break;
            case 'DB_NAME':
                $this->db = $this->desencriptarTexto($value);
                break;
            case 'SECRET_KEY':
                $this->secretKey = $this->desencriptarTexto($value);
                break;
        }
    }

    private function desencriptarTexto($textoEncriptado): string
    {
        $datos = base64_decode($textoEncriptado);
        $ivLength = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($datos, 0, $ivLength);
        $textoEncriptado = substr($datos, $ivLength);

        $resultado = openssl_decrypt($textoEncriptado, 'aes-256-cbc', $this->key, 0, $iv);

        if ($resultado === false) {
            $this->debug("Error de OpenSSL", openssl_error_string());
            return '';
        }

        return $resultado;
    }

    private function connect(): void
    {
        try {
            $this->conexion = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conexion->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            throw new Exception("Error al conectar a la base de datos: " . $e->getMessage() . "\nHost: $this->host\nDB: $this->db");
        }
    }

    public function getConexion(): PDO
    {
        return $this->conexion;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function debug($var, $val = '-'): void
    {
        if ($this->debug) {
            $file = fopen("../debug.log", "a") or die("Error creando archivo");
            $texto = '[' . date("Y-m-d H:i:s") . ']::[' . $var . ']:-> [' . $val . ']';
            fwrite($file, $texto . PHP_EOL) or die("Error escribiendo en el archivo");
            fclose($file);
        }
    }
}