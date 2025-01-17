<?php

namespace Promptopolis\Framework;

class Moderator extends User
{
    public function approve($id, $authorID)
    {
        self::updateApprove($id);
        $credits = \Promptopolis\Framework\User::getCredits($authorID);
        $credits += 2;
        \Promptopolis\Framework\User::updateCredits($authorID, $credits);     
    }

    public function updateApprove($id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE prompts SET is_approved = 1, is_reported = 0 WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public function deny($id, $message)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE prompts SET is_denied = 1, is_approved = 0, message = :message WHERE id = :id");
        $statement->bindValue(":message", $message);
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public function updateVotes($id, $loggedInUser_id)
    {
        $conn = Db::getInstance();
        //if the current user has already voted for the user, he cannot vote again
        $statement = $conn->prepare("SELECT * FROM user_vote WHERE voted_for = :voted_for AND voted_by = :voted_by");
        $statement->bindValue(":voted_for", $id);
        $statement->bindValue(":voted_by", $loggedInUser_id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        //if the user has not voted for the user yet, insert the vote into the database
        if (!$result) {
            $statement = $conn->prepare("INSERT INTO user_vote (voted_for, voted_by) VALUES (:voted_for, :voted_by)");
            $statement->bindValue(":voted_for", $id);
            $statement->bindValue(":voted_by", $loggedInUser_id);
            $statement->execute();
        }
        return $result;
    }

    public function checkStatus($id)
    {
        //check if the user is admin
        $admin = User::isModerator($id);

        if (User::isModerator($id) == 0) {
            $this->checkToMakeAdmin($id);
        } else {
            $this->checkToRemoveAdmin($id);
        }
    }

    public function checkToRemoveAdmin($id)
    {
        //if a user has more than 2 votes, he will be removed as admin
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM user_vote WHERE voted_for = :voted_for");
        $statement->bindValue(":voted_for", $id);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if (count($result) >= 2) {
            $statement = $conn->prepare("UPDATE users SET is_admin = 0 WHERE id = :id");
            $statement->bindValue(":id", $id);
            $statement->execute();
            //if the user is removed as admin, remove all votes for that user
            $statement = $conn->prepare("DELETE FROM user_vote WHERE voted_for = :voted_for");
            $statement->bindValue(":voted_for", $id);
            $statement->execute();
        }
    }

    public function checkToMakeAdmin($id)
    {
        $conn = Db::getInstance();
        //get all the rows where the user has been voted for
        $statement = $conn->prepare("SELECT * FROM user_vote WHERE voted_for = :user_id");
        $statement->bindValue(":user_id", $id);
        $statement->execute();
        //count the amount of rows and return it
        $count = $statement->rowCount();
        //if the user has been voted for 2 times, he becomes an admin
        if ($count == 2) {
            $statement = $conn->prepare("UPDATE users SET is_admin = 1 WHERE id = :id");
            $statement->bindValue(":id", $id);
            $statement->execute();
            //remove the votes for this user
            $statement = $conn->prepare("DELETE FROM user_vote WHERE voted_for = :user_id");
            $statement->bindValue(":user_id", $id);
            $statement->execute();
        }
    }
}
