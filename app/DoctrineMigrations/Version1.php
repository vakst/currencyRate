<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version1 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql("CREATE TABLE currency_rate
            (
              id serial NOT NULL,
              rate real NOT NULL,
              pair character varying(32) NOT NULL,
              update_date timestamp without time zone NOT NULL DEFAULT now(),
              CONSTRAINT PK_CURRENCY_RATE_ID PRIMARY KEY (id),
              CONSTRAINT UNIQ_PAIR_RATE UNIQUE (rate,pair)
            )
            WITH (
              OIDS=FALSE
            );"
        );
        $this->addSql("ALTER TABLE currency_rate OWNER TO currency_rate;");
        $this->addSql("COMMENT ON COLUMN currency_rate.rate IS 'current rate value';");
        $this->addSql("COMMENT ON COLUMN currency_rate.pair IS 'ex. USD/RUB';");

        $this->addSql("CREATE TABLE currency_rate_log
        (
          id serial NOT NULL,
          rate real NOT NULL,
          pair character varying(32) NOT NULL,
          created_date timestamp with time zone NOT NULL DEFAULT now(),
          CONSTRAINT PK_CURRENCY_RATE_LOG_ID PRIMARY KEY (id)
        )
        WITH (
          OIDS=FALSE
        );");

        $this->addSql("ALTER TABLE currency_rate OWNER TO currency_rate;");
        $this->addSql("CREATE OR REPLACE FUNCTION p_currency_rate_log() RETURNS TRIGGER AS $$
            BEGIN
            IF  TG_OP = 'UPDATE' AND OLD.update_date = NEW.update_date THEN
                NEW.update_date = NOW();
            END IF;

            IF    TG_OP = 'INSERT'  OR  NEW.rate <> OLD.rate THEN
                INSERT INTO currency_rate_log(rate,pair,created_date) VALUES (NEW.rate,NEW.pair,NEW.update_date);
            END IF;
            RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;"
        );

        $this->addSql("CREATE TRIGGER currency_rate_log
            AFTER INSERT OR UPDATE
            ON currency_rate
            FOR EACH ROW
            EXECUTE PROCEDURE p_currency_rate_log();"
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }
}
